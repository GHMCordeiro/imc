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
                        <h2>CÁLCULO DO IMC</h2>
                        <?php

                        $erros = [];
                        if (count($_POST) > 0) {
                            $dados = $_POST;

                            if (trim($dados['nome']) === "") {
                                $erros['nome'] = "Nome é obrigatório";
                            }

                            $alturaConfig = ['options' => ['decimal' => ',', 'min_range' => 0, 'max_range' => '3',]];

                            if (!filter_var($dados['altura'], FILTER_VALIDATE_FLOAT, $alturaConfig) && $dados['altura'] != 0) {

                                $erros['altura'] = "Altura inválida, informe em metros";
                            }

                            $pesoConfig = ['options' => ['decimal' => ',']];

                            if (!filter_var($dados['peso'], FILTER_VALIDATE_FLOAT, $pesoConfig)) {
                                $erros['peso'] = "Digite o peso em quilos e com virgula (,) ao invés de ponto (.)";
                            }

                            if (count($erros) == 0) {
                                require_once("conexao.php");

                                $sql = "INSERT INTO imc (nome, peso, altura, imc, descImc) values (?, ?, ?, ?, ?)";

                                $conexao = novaConexao();
                                $insert = $conexao->prepare($sql);

                                $peso = $dados['peso'] ? str_replace(",", ".", $dados['peso']) : null;
                                $altura = $dados['altura'] ? str_replace(",", ".", $dados['altura']) : null;

                                $imc = number_format(($dados['peso'] / ($altura * $altura)), 2);
                                $nome = $dados['nome'];


                                if ($imc < 18.5) {
                                    $descImc = "Seu IMC é " . $imc . ", e trata-se de Baixo Peso";
                                } elseif (($imc > 18.5) && ($imc < 25)) {
                                    $descImc =  "Seu IMC é " . $imc .", e trata-se de Peso Normal";
                                } elseif (($imc >= 25) && ($imc < 30)) {
                                    $descImc =  "Seu IMC é " . $imc .", e trata-se de Pré-obesidade";
                                } elseif (($imc >= 30) && ($imc < 35)) {
                                    $descImc =  "Seu IMC é " . $imc .", e trata-se de Obesidade Grau 1";
                                } elseif (($imc >= 35) && ($imc < 40)) {
                                    $descImc =  "Seu IMC é " . $imc .", e trata-se de Obesidade Grau 2";
                                } else {
                                    $descImc = "S-eu IMC é " . $imc .", e trata-se de Obesidade Grau 3";
                                }

                                $params = [
                                    $nome,
                                    $peso,
                                    $altura,
                                    $imc ? str_replace(",", ".", $imc) : null,
                                    $descImc
                                ];

                                 $insert->bind_param("sddds", ...$params);

                                 if ($insert->execute()) {
                                     unset($dados);
                                 }

                                 echo "
                                 <script>
                                     alert('Calculado e cadastrado com sucesso!');
                                 </script>
                                 ";
                                 header('Location: home.php');
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
                                    <input type="text" class="form-control <?php echo isset($erros['nome']) ? 'is-invalid' : '' ?>" id="id_nome" name="nome" placeholder="Nome" value="<?php echo isset($dados['nome']) ? $dados['nome'] : '' ?>">
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
                                    <input type="text" class="form-control <?php echo isset($erros['peso']) ? 'is-invalid' : '' ?>" id="id_peso" name="peso" placeholder="Peso" value="<?php echo isset($dados['peso']) ? $dados['peso'] : '' ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $erros['peso']; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-md-10">
                                    <label for="altura">Altura:</label>
                                    <input type="text" class="form-control <?php echo isset($erros['altura']) ? 'is-invalid' : '' ?>" id="id_altura" name="altura" placeholder="Altura" value="<?php echo isset($dados['altura']) ? $dados['altura'] : '' ?>">
                                    <div class="invalid-feedback">
                                        <?php echo $erros['altura'] ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary btn-lg">Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>