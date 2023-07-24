<?php
    session_start();
    include("connection.php");

    $_SESSION['message'] = "";
    $_SESSION['cpf'] = "";
    $_SESSION['loggedIn'] = false;

    session_destroy();
    header("Location: index.php");
?>