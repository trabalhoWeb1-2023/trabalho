<!-- 
    TÁ FALTANDO aqui

    - Corrigir o bug do echo, ta escrevendo alguma coisa na tela qnd manda email, antes de voltar pro index e alertar q enviou o email
    - Corrigir bug do alert q n sai, igual tava rolando no do jogo da velha
    - URL DO LOGIN VER SE VAI DEIXAR ASSIM
-->

<?php
    session_start();
    include("connection.php");

    $_SESSION['message'] = "";

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
            $mail->Username = 'sistema.web.ufrrj@gmail.com';
            $mail->Password = 'bxtnvotgnzyusahb'; // tive que por pra fazer verificação em dois passos e pagar uma senha de app na parte de verificação em dois passos
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('sistema.web.ufrrj@gmail.com', 'Sistema'); // Remetente
            $mail->addAddress($destinatario); // Destinatário
            $mail->Subject = $assunto; // Assunto do e-mail
            $mail->Body = $mensagem; // Corpo do e-mail

            $mail->send();

            $_SESSION['message'] = "Enviamos um e-mail para você! \nConfira seu e-mail, caixa de spam e lixeira.";

            return true;
        } catch (Exception $e) {
            $_SESSION['message'] = "Houve um erro. Tente novamente.";

            return false;
        }
    }

    $url = "http://localhost/projetos/trabalho/src/login.php"; // @

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $cpf = $_POST['cpf'];
        $_SESSION['cpf'] = $cpf;
    
        $sql_query = "SELECT email, mudou_senha, senha FROM respondente WHERE cpf=$cpf";
        $result = mysqli_query($connection, $sql_query);
    
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $emailBD = $row["email"];
                $senhaBD = $row["senha"];
                $mudou_senha = $row["mudou_senha"];
            }
        } else {
            $_SESSION['message'] = "CPF não cadastrado. Faça o cadastro.";
            header("Location: index.php");	
            exit();
        }
    
        // Caso a pessoa esteja no primeiro acesso, envia o email. Caso contrario, vai direto para página de login
    
        if ($mudou_senha == 0) {
            $body = "Bem-vindo!" . "\n\n" . "Sua senha é: " . $senhaBD . "\n" . "Acesse aqui: " . $url . "\n";
            $assunto = 'Acesse sua conta';
            
            enviarEmail($emailBD, $assunto, $body);
        } else {
            header("Location: login.php");	
            exit();
        }
    }

?>

<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">
	<link rel="stylesheet" href="./css/style.css">
</head>
<body></body>
</html>