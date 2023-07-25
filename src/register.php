<?php
include("functions.php");

$name = "";
$cpf = "";
$birthdate = "";
$weight = "";
$height = "";
$sleeptime = "";
$email = "";
$sql = "";
$senha = "";

if ($connection->connect_error) 
    die("falha na conexão: " . $connection->connect_error);
 else
    echo "Conectado com BD!!";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $cpf = $_POST["cpf"];
    $birthdate = $_POST["birthdate"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $sleeptime = $_POST["hours_of_sleep"];
    $email = $_POST["userEmail"];

    // Verificações de validação
    if (!isValidCPF($cpf)) {

        $_SESSION['message'] = "CPF inválido! Por favor, verifique o CPF digitado.";
        header("Location: index.php");	
        exit();
        // Se o CPF for inválido, você pode abortar o processo ou fazer alguma ação específica
    } elseif (!isValidEmail($email)) {
        
        $_SESSION['message'] = "Email inválido! Por favor, verifique o email digitado.";
        header("Location: index.php");	
        exit();
        // Se o email for inválido, você pode abortar o processo ou fazer alguma ação específica
    } else {
        // Se o CPF e o email forem válidos, proceda com a inserção no banco de dados
        $newcpf = isValidCPF($cpf);
        $sql = "INSERT INTO `respondente`(`nome`, `cpf`, `data_nasc`, `peso`, `altura`, `horas_sono_dia`, `email`, `senha`)
                    VALUES('$name', '$newcpf', '$birthdate', '$weight', '$height', '$sleeptime', '$email', '$birthdate')";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            $_SESSION['message'] = "Usuário cadastrado com sucesso!";
            header("Location: index.php");	
            exit();
        } else {
            $_SESSION['message'] = "Erro ao inserir os dados no banco de dados: " . mysqli_error($connection);
            header("Location: index.php");	
            exit();
        }
    }
} else
    echo "erro";



?>