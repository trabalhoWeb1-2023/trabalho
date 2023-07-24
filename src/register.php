<?php

include("connection.php");

$name = "";
$cpf = "";
$birthdate = "";
$weight = "";
$height = "";
$sleeptime = "";
$email = "";
$sql = "";

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

        echo "CPF inválido! Por favor, verifique o CPF digitado.";
        // Se o CPF for inválido, você pode abortar o processo ou fazer alguma ação específica
    } elseif (!isValidEmail($email)) {
        echo "Email inválido! Por favor, verifique o email digitado.";
        // Se o email for inválido, você pode abortar o processo ou fazer alguma ação específica
    } else {
        // Se o CPF e o email forem válidos, proceda com a inserção no banco de dados
        $newcpf = isValidCPF($cpf);
        $sql = "INSERT INTO `respondente`(`nome`, `cpf`, `data_nasc`, `peso`, `altura`, `horas_sono_dia`, `email`)
                    VALUES('$name', '$newcpf', '$birthdate', '$weight', '$height', '$sleeptime', '$email')";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "Dados inseridos no banco de dados com sucesso!";
        } else {
            echo "Erro ao inserir os dados no banco de dados: " . mysqli_error($connection);
        }

        /*  $sql = "INSERT INTO `respondente`(`nome`, `cpf`, `data_nasc`, `peso`, `altura`, `horas_sono_dia`, `email`)
                VALUES('$name', '$cpf', '$birthdate', '$weight', '$height', '$sleeptime', '$email')";
*/
    }
} else
    echo "erro";
    
//@param string $base 
// @return string

function calcularDigitoVerificador($base)
{
    // VAR AUX
    $tamanho = strlen($base); 
    $multiplicador = $tamanho + 1; 
    $soma = 0;

    for ($i = 0; $i < $tamanho; $i++) {
        $soma += $base[$i] * $multiplicador;
        $multiplicador--;
    }

    $resto = $soma % 11;

    if ($resto == 0 || $resto == 1)
        return 0;

    else
        return 11 - $resto;
}

function isValidCPF($cpf)
{
    // Remover quaisquer caracteres que não sejam números
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verificar se o CPF tem 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }
    // digito verificador 
    $cpfValidacao = substr($cpf, 0, 9);
    $cpfValidacao .= calcularDigitoVerificador($cpfValidacao);
    $cpfValidacao .= calcularDigitoVerificador($cpfValidacao);

    if ($cpfValidacao == $cpf)
        return $cpf;
    else
        return false;
}

function isValidEmail($email)
{

    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    // verifica se o email é o igual ao modelo
    if (preg_match($pattern, $email) === 1)
        return true;
    else
        return false;
}
