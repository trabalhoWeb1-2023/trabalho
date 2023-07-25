<?php
    session_start();
    include("connection.php");

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $cpfUser = $_POST['cpf'];
        $senha = $_POST['senha'];

        $_SESSION['cpf'] = $cpfUser;

        $sql_query = "SELECT mudou_senha, senha FROM respondente WHERE cpf=?";

        // Prepared Statements
        $stmt = $connection->prepare($sql_query);
        $stmt->bind_param("s", $cpfUser);
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cpfBD = $row["cpf"];
                $senhaBD = $row["senha"];
                $mudou_senha = $row["mudou_senha"];
            }

            if ($senha == $senhaBD) {
                $_SESSION['loggedIn'] = true;

                // Caso seja o primeiro acesso, vai para tela de mudar senha. Caso contrário, vai para home normalmente

                if ($mudou_senha == 0) {
                    header("Location: changePass.php");	
                    exit();
                } else {
                    header("Location: home.php");	
                    exit();
                }
            } else {
                $_SESSION['loginMessage'] = "Senha incorreta.";
                header("Location: login.php");	
                exit();
            }
        } else {
            $_SESSION['message'] = "Usuário incorreto. Tente novamente.";
            header("Location: ../index.php");	
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
    <title>Entrar</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Entrar</h1>
    
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="input-wrapper">
            <label for="cpf">CPF</label>
            <input type="number" required name="cpf" id="cpf" />
        </div>
        <div class="input-wrapper">
            <label for="senha">Senha</label>
            <input type="password" required name="senha" id="senha" />
        </div>
        <button type="submit">Entrar</button>   
    </form>

    <script>
        const cpfInput = document.querySelector("#cpf");
        const senhaInput = document.querySelector("#senha");
        let cpf = <?php echo isset($_SESSION['cpf']) ? json_encode($_SESSION['cpf']) : json_encode(""); ?>;
        let loginMessage = <?php echo isset($_SESSION['loginMessage']) ? json_encode($_SESSION['loginMessage']) : json_encode(""); ?>;
        
        if (cpf != "") {
            cpfInput.value = cpf;
        }
        if (loginMessage != "") {
            alert(loginMessage);
        }

        senhaInput.focus();
    </script>
</body>
</html>