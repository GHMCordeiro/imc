<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Formulário Inserindo Dados Mysql</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 border rounded p-3 bg-light">
                <div class="row justify-content-center">
                    <div class="col-md-10 text-center mt-3">
                        <h2>ALTERAR DADOS</h2>
                        <?php

                        require_once("conexao.php");


                        if (isset($_GET['codigo'])) {



                            $sql = "SELECT * FROM imc WHERE id = ? ";

                            $conexao = novaConexao();

                            $alterar = $conexao->prepare($sql);

                            $alterar->bind_param("i", $_GET['codigo']);


                            if ($alterar->execute()) {
                                $resultado = $alterar->get_result();


                                if ($resultado->num_rows > 0) {

                                    $dados = $resultado->fetch_assoc();
                                    $altura = $dados['altura'] ? str_replace(".", ",", $dados['altura']) : null;
                                }

                                if (isset($_POST['submit'])) {

                                    $erros = [];
                                    if (count($_POST) > 0) {
                                        $dados2 = $_POST;

                                        if (trim($dados2['nome']) === "") {
                                            $erros['nome'] = "Nome é obrigatório";
                                        }
                                        
                                        $alturaConfig = ['options' => ['decimal' => ',', 'min_range' => 0, 'max_range' => '3',]];

                                        if (!filter_var($dados2['altura'], FILTER_VALIDATE_FLOAT, $alturaConfig) && $dados2['altura'] != 0) {

                                            $erros['altura'] = "Altura inválida, informe em metros";
                                        }

                                        $pesoConfig = ['options' => ['decimal' => ',']];

                                        if (!filter_var($dados2['peso'], FILTER_VALIDATE_FLOAT, $pesoConfig)) {
                                            $erros['peso'] = "Digite o peso em quilos e com virgula (,) ao invés de ponto (.)";
                                        }
                                    }

                                    if (count($erros) == 0) {

                                        $sql2 = "UPDATE imc SET nome = ?, altura = ?, peso = ?, imc = ?, descImc = ? WHERE id = ?";

                                        $peso = $dados2['peso'] ? str_replace(",", ".", $dados2['peso']) : null;
                                        $altura2 = $dados2['altura'] ? str_replace(",", ".", $dados2['altura']) : null;

                                        $imc = number_format(($peso / ($altura2 * $altura2)), 2);
                                        $nome = $dados2['nome'];
                                        $altera = $conexao->prepare($sql2);

                                        if ($imc < 18.5) {
                                            $descImc = "Seu IMC e " . $imc . ", e trata-se de Baixo Peso";
                                        } elseif (($imc > 18.5) && ($imc < 25)) {
                                            $descImc =  "Seu IMC e " . $imc . ", e trata-se de Peso Normal";
                                        } elseif (($imc >= 25) && ($imc < 30)) {
                                            $descImc =  "Seu IMC e " . $imc . ", e trata-se de Pré-obesidade";
                                        } elseif (($imc >= 30) && ($imc < 35)) {
                                            $descImc =  "Seu IMC e " . $imc . ", e trata-se de Obesidade Grau 1";
                                        } elseif (($imc >= 35) && ($imc < 40)) {
                                            $descImc =  "Seu IMC e " . $imc . ", e trata-se de Obesidade Grau 2";
                                        } else {
                                            $descImc = "Seu IMC e " . $imc . ", e trata-se de Obesidade Grau 3";
                                        }

                                        $params = [
                                            $nome,
                                            $altura2,
                                            $peso,
                                            $imc ? str_replace(",", ".", $imc) : null,
                                            $descImc,
                                            $dados['id']
                                        ];

                                        $altera->bind_param("sdddsi", ...$params);

                                        if ($altera->execute()) {
                                            unset($dados);

                                        }

                                        echo "
                                     <script>
                                         alert('Alterado com sucesso!');
                                     </script>
                                     ";
                                        header('Location: home.php');
                                    }
                                }
                            }
                        }
                        ?>

                    </div>
                </div>
                <div class="row justify-content-center mt-4">
                    <div class="col-md-10 text-center">
                        <form method="POST">
                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-10">

                                    <label for="nome">Nome:</label>
                                    <input type="text" class="form-control" id="id_nome" name="nome" placeholder="Nome" value="<?php echo isset($dados['nome']) ? $dados['nome'] : '' ?>">
                                    <div class="invalid-feedback">
                                        <?php
                                        echo $erros['nome'];
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-10">
                                    <label for="peso">Peso:</label>
                                    <input type="text" class="form-control" id="id_peso" name="peso" placeholder="Peso" value="<?php echo isset($dados['peso']) ? $dados['peso'] : '' ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $erros['peso']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-10">
                                    <label for="altura">Altura:</label>
                                    <input type="text" class="form-control" id="id_altura" name="altura" placeholder="Altura" value="<?php echo isset($altura) ? $altura : '' ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $erros['altura'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary btn-lg" name="submit">Alterar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>