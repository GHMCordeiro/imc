<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Banco de Dados</title>
</head>
<body>
    <h1>CRIANDO BANCO DE DADOS</h1>
    <?php
       
        require("conexao.php");

       
        $conexao = novaConexao(null); 

        $sql='CREATE DATABASE IF NOT EXISTS crud_imc';

        $resultado = $conexao->query($sql); 

        if($resultado){
           header('Location: criar_tabela.php');
            
        }else{
            echo ":( Erro: ".$conexao->error;
        }

        $conexao->close();
       
    ?>
</body>
</html>
