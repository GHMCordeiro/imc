<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <title>Consultar Dados</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5 mb-3">
            <div class="col-md-10 text-center">
                <h1>Dados de IMC </h1>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <?php

                require_once("conexao.php");

                

                $sql = "SELECT * FROM imc";

                //vamos criar uma nova conexão
                $conexao = novaConexao();

                //Executando a seleção de dados no banco
                $resultado = $conexao->query($sql);

                //vamos criar um array vazio, para receber os dados vindos do banco de dados
                $registros = [];

                //vamos verificar se o resultado possui linhas
                if ($resultado->num_rows > 0) { //num_row é para verificar o numero de linhas e caso seja maior 0 significa que temos registros em nossa tabela.
                    //criar um laço de repetição para verificar os registros e armazenar no array vazio criado.
                    while ($row = $resultado->fetch_assoc()) {
                        //Foi criado uma variavel dentro do while para receber um Array Associativo, ou seja, (Array chave valor), onde a chave será o nome da coluna e o valor será o valor correspondente aquela coluna. Gerando assim um array para cada linha existente.
                        $registros[] = $row; // Inserindo todos os registros dentro de nosso array
                    }
                } else if ($conexao->error) {
                    echo ":( Erro: " . $conexao->error;
                }

                $conexao->close();

                ?>

                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th scope="col" class="border bg-dark text-white">Nome</th>
                            <th scope="col" class="border bg-dark text-white">Peso</th>
                            <th scope="col" class="border bg-dark text-white">altura</th>
                            <th scope="col" class="border bg-dark text-white">Imc</th>
                            <th scope="col" class="border bg-dark text-white">Descrição</th>
                            <th scope="col" class="border bg-dark text-white">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro) : ?>
                            <tr>
                                <th class="border" scope="row"><?= $registro['nome'] ?></th>
                                <td class="border"><?= $registro['peso'] ?></td>
                                <td class="border"><?= $registro['altura'] ?></td>
                                <td class="border"><?= $registro['imc'] ?></td>
                                <td class="border"><?= $registro['descImc'] ?></td>
                                <td class="border d-flex justify-content-around"><?php echo "<a href='alterar_dados.php?codigo=" . $registro['id'] . "' class='bi bi-pencil text-dark'></a> <a href='deletar_dado.php?codigo=" . $registro['id'] . "' class='bi bi-trash text-danger'></a>" ?></td>

                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 text-center">
                <a href="Inserir_dados.php" class="btn btn-dark">Calcular IMC</a>
            </div>
        </div>

    </div>


    <!--Proximo arquivo será Excluir_Dados.php -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>