<?php
session_start();
$_SESSION['message'] = "";

include("connection.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function enviarEmail($destinatario, $assunto, $mensagem) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP do Gmail
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lucyanovidio@gmail.com';
        $mail->Password = 'emokzeisfhapbllt'; // tive que por pra fazer verificação em dois passos e pagar uma senha de app na parte de verificação em dois passos
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('lucyanovidio@gmail.com', 'Lucyan'); // Remetente
        $mail->addAddress($destinatario); // Destinatário
        $mail->Subject = $assunto; // Assunto do e-mail
        $mail->Body = $mensagem; // Corpo do e-mail

        $mail->send();

		$_SESSION['message'] = "E-mail enviado!";

        return true;
    } catch (Exception $e) {
		$_SESSION['message'] = "Houve um erro. Tente novamente.";

        return false;
    }
}

// ter um script aqui pra caso a pessoa ja ter mudado a senha, n precisar mandar email dnv, só jogar pra home.

$row;
$email_to;
$senha;

$url = "URL do login aqui";
$cpf = $_POST['cpf'];

$sql_query = "SELECT email, senha FROM respondente WHERE cpf=$cpf";
$result = mysqli_query($connection, $sql_query);

if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$email_to = $row["email"];
		$senha = $row["senha"];
	}
} else {
	$_SESSION['message'] = "CPF não cadastrado. Faça o cadastro.";
	header("Location: index.php");	
	exit();
}

$body = 'Bem-vindo!' . '\n' . 'Sua senha é: ' . $senha . "\n" . 'Acesse aqui: ' . $url;

$assunto = 'Teste de envio de e-mail';

enviarEmail($email_to, $assunto, $body);
?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">
	<link rel="stylesheet" href="./css/style.css">
</head>
<body></body>
</html>