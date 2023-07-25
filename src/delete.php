<?php
    session_start();
    include("connection.php");

    $cpf = $_SESSION['cpf'];

    $sql_query = "UPDATE respondente SET deletado=1 WHERE cpf=$cpf";
    $result = mysqli_query($connection, $sql_query);

    header("Location: logout.php");
    exit();
?>