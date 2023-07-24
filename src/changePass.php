<?php
    session_start();
    include("connection.php");
    $_SESSION['loginMessage'] = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $cpf = $_SESSION['cpf'];
        $senha_antiga = $_POST['senha-antiga'];
        $senha_nova = $_POST['senha-nova'];

        $sql_query = "SELECT mudou_senha, senha FROM respondente WHERE cpf=?";

        // Prepared Statements
        $stmt = $connection->prepare($sql_query);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $senhaBD = $row["senha"];
                $mudou_senha = $row["mudou_senha"];
            }
        }
        
        if ($senha_antiga == $senhaBD) {
            $sql_query = "UPDATE respondente SET senha=?, mudou_senha=1  WHERE cpf=?";
            
            // Prepared Statements
            $stmt = $connection->prepare($sql_query);
            $stmt->bind_param("ss", $senha_nova, $cpf);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Senha atualizada com sucesso!";
                header("Location: home.php");	
	            exit();
            } else {
                $_SESSION['message'] = "Houve um erro na atualização da senha. Tente de novo.";
                header("Location: changePass.php");	
	            exit();
            }
        } else {
            $_SESSION['message'] = "A senha antiga está incorreta.";
            header("Location: changePass.php");	
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Altere a senha</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Altere a senha para continuar</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="input-wrapper">
            <label for="senha-antiga">Senha antiga</label>
            <input type="password" required name="senha-antiga" id="senha-antiga" />
        </div>
        <div class="input-wrapper">
            <label for="senha-nova">Senha nova</label>
            <input type="password" required name="senha-nova" id="senha-nova" />
        </div>
        <button type="submit">Alterar e continuar</button>
    </form>

    <script>
        let message = <?php echo isset($_SESSION['message']) ? json_encode($_SESSION['message']) : json_encode(""); ?>;
        let message2 = <?php echo isset($_SESSION['message2']) ? json_encode($_SESSION['message2']) : json_encode(""); ?>;
        let executou = <?php echo isset($_SESSION['changePassExecutou']) ? json_encode($_SESSION['changePassExecutou']) : json_encode("Teve erro"); ?>;

        if (message2 != "") {
            alert(message);
        }
        if (!executou) {
            if (message != "") {
                alert(message);
            }
        }

        <?php
            $_SESSION['changePassExecutou'] = false;
        ?>
    </script>
</body>
</html>