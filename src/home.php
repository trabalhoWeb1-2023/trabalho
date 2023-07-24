<?php
    session_start();
    include("connection.php");
    $_SESSION['loginMessage'] = "";
    $_SESSION['changePassExecutou'] = true;

    if ($_SESSION['loggedIn'] == false) {
        $_SESSION['message'] = "Você precisa logar primeiro.";
        header("Location: index.php");
        exit();
    }

    $cpf = $_SESSION['cpf'];
    $userData = [];
    $userDataNames = ["Nome", "E-mail", "Data de nascimento", "CPF", "Peso", "Altura"];
    
    $sql_query = "SELECT nome, email, data_nasc, cpf, peso, altura, mudou_senha FROM respondente WHERE cpf=$cpf";
    $result = mysqli_query($connection, $sql_query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($userData, $row["nome"], $row["email"], $row["data_nasc"], $row["cpf"], $row["peso"], $row["altura"]);
            $mudou_senha = $row["mudou_senha"];
        }
    }

    if ($mudou_senha == 0) {
        $_SESSION['message2'] = "Você precisa alterar a senha primeiro.";
        header("Location: changePass.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <h1>Home</h1>

    <div class="data">
        <h2>Dados:</h2>
        <?php
            for ($i = 0; $i < count($userData); $i++) {
                echo "<p>" . $userDataNames[$i] . ": " . $userData[$i] . "</p>";
            }
        ?>
    </div>

    <br>
    <a href="logout.php">Sair</a>

    <script>
        const logoutBtn = document.querySelector("a");
        let message = <?php echo isset($_SESSION['message']) ? json_encode($_SESSION['message']) : json_encode(""); ?>;
        let executou = <?php echo isset($_SESSION['homeExecutou']) ? json_encode($_SESSION['homeExecutou']) : json_encode("Teve erro"); ?>;

        logoutBtn.addEventListener("click", () => alert("Você saiu."));

        if (!executou) {
            if (message != "") {
                alert(message);
                <?php
                    $_SESSION['homeExecutou'] = true;
                ?>
            }
        }
    </script>
</body>
</html>