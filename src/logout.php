<?php
    session_start();
    include("connection.php");

    $_SESSION['message'] = "";
    $_SESSION['cpf'] = "";
    $_SESSION['loggedIn'] = false;
    $_SESSION['message2'] = "";

    session_destroy();
    header("Location: ../index.php");
?>