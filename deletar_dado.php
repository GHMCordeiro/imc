<?php
    require_once("conexao.php");
    $conexao = novaConexao();    

    if (isset($_GET['codigo'])){     
        $excluirSQL = "DELETE FROM imc WHERE id = ?"; 
        $stmt = $conexao->prepare($excluirSQL);  
        $stmt->bind_param("i", $_GET['codigo']);
        $stmt->execute(); 

    }   

    $conexao->close();
    
    header('Location: home.php');

    ?>
