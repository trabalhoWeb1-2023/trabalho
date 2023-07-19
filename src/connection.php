<?php
    $servername ="localhost";
    $usuario="root";
    $senha="";
    $bd ="trabalho_web1_2023";

    $connection = mysqli_connect($servername, $usuario, $senha, $bd);

    // if(!$connection){
    //     die("falha ao conectar:" . $connection->error);
    // }
    // else
    //     echo "Conectado";
?>  