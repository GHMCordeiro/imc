<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Tabela</title>
</head>

<body>
    <h1>Criar Tabela</h1>
    <?php

    require_once("conexao.php");

    $conexao = novaConexao();

    $sql = "SELECT COUNT(*)
    FROM information_schema.tables 
    WHERE table_schema = 'crud_imc' 
    AND table_name = 'imc';";

    $result = $conexao->query($sql);

    if (mysqli_num_rows($result)< 1) {
        header('Location: home.php');
    } else {
        $sqlTab = "CREATE TABLE IF NOT EXISTS imc(
            id int(6) auto_increment,
            nome varchar(50) not null,
            peso float not null,
            altura float not null,
            imc float not null,
            descImc varchar(255) not null,
            primary key(id)
        )";



        $resultadoTab = $conexao->query($sqlTab);

        if ($resultadoTab) {
            header('Location: home.php');
        } else {
            echo ":( Erro: " . $conexao->error;
        }
    }



    ?>


</body>

</html>