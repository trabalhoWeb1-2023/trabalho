<?php
// fazer um if, se for email, usa a função de validar pra dps armazenar
    session_start();
    include("connection.php");
    
    $newValue = $_GET['newValue'];
    $columnName = $_GET['className'];
    $cpf = $_SESSION['cpf'];

    $sql_query = "UPDATE respondente SET $columnName=?  WHERE cpf=?";
    
    // Prepared Statements
    $stmt = $connection->prepare($sql_query);
    $stmt->bind_param("ss", $newValue, $cpf);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Atualização feita com sucesso!";
        header("Location: home.php");	
        exit();
    } else {
        $_SESSION['message'] = "Houve um erro na atualização de: $columnName. Tente de novo.";
        header("Location: home.php");	
        exit();
    }
?>