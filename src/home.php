<?php
    session_start();
    include("connection.php");
    $_SESSION['loginMessage'] = "";

    if ($_SESSION['loggedIn'] == false) {
        $_SESSION['message'] = "Você precisa logar primeiro.";
        header("Location: index.php");
        exit();
    }

    $cpf = $_SESSION['cpf'];
    $userData = [];
    $userDataNames = ["Nome", "E-mail", "Data de nascimento", "CPF", "Peso", "Altura", "E-mail secundário"];
    $userDataBDNames = ["nome", "email", "data_nasc", "cpf", "peso", "altura", "novos_emails"];

    $sql_query = "SELECT nome, email, data_nasc, cpf, peso, altura, mudou_senha, novos_emails FROM respondente WHERE cpf=$cpf";
    $result = mysqli_query($connection, $sql_query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($userData, $row["nome"], $row["email"], $row["data_nasc"], $row["cpf"], $row["peso"] . " kg", $row["altura"] . " m", $row["novos_emails"] ? $row["novos_emails"] : "");
            $mudou_senha = $row["mudou_senha"];
        }
    }

    if ($mudou_senha == 0) {
        $_SESSION['message'] = "Você precisa alterar a senha primeiro.";
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
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="./css/style.css" />
</head>
<body>
    <h1>Boas-vindas, <?= $userData[0]; ?>!</h1>

    <div class="data">
        <h2>Dados:</h2>

        <?php
            for ($i = 0; $i < count($userData); $i++) {
                $data = $userData[$i] != "" ? $userData[$i] : "Insira um segundo e-mail";

                if ($userDataBDNames[$i] == "email") {
                    $string = 
                        "<p>
                            <span>" . 
                                $userDataNames[$i] . ": " . 
                                "<span class='$userDataBDNames[$i]'>$data</span>
                            </span>" . 
                        "</p>";  
                } else {
                    $string = 
                        "<p>
                            <span>" . 
                                $userDataNames[$i] . ": " . 
                                "<span class='$userDataBDNames[$i]'>$data</span>
                            </span>" .
                            "<span>
                                <i data-feather='edit-2'></i>
                                <i data-feather='check' class='hide'></i>
                                <i data-feather='x' class='hide'></i>
                            </span>" . 
                        "</p>";
                }
                
                echo $string;
            }
        ?>
    </div>

    <a href="changePass.php">Alterar senha</a>
    <a href="delete.php" id="deleteAccountBtn">Deletar conta</a>
    <a href="logout.php" id="logoutBtn">Sair</a>

    <script>
        const logoutBtn = document.querySelector("a#logoutBtn");
        const deleteAccountBtn = document.querySelector("#deleteAccountBtn");
        let message = <?php echo isset($_SESSION['message']) ? json_encode($_SESSION['message']) : json_encode(""); ?>;
        
        logoutBtn.addEventListener("click", () => alert("Você saiu!"));
        deleteAccountBtn.addEventListener("click", () => alert("Conta excluída! Você está sendo redirecionado."));
        
        if (message != "") {
            alert(message);
        }
        
        setTimeout(()=> {
            const editBtns = document.querySelectorAll(".data p svg:nth-child(1)");

            editBtns.forEach(editBtn => {
                editBtn.addEventListener("click", () => {
                    const closeBtn = editBtn.parentNode.children[2];
                    const currentData = editBtn.parentNode.parentNode.children[0].children[0];
                    const confirmBtn = editBtn.parentNode.children[1];

                    let data = currentData.innerHTML;
                    currentData.innerHTML = "<input type='text' />"
                    currentData.querySelector("input").value = data;
                    
                    confirmBtn.classList.remove("hide");
                    closeBtn.classList.remove("hide");
                    editBtn.classList.add("hide");

                    confirmBtn.addEventListener('click', update);
                    
                    function update() {
                        let newValue = currentData.children[0].value;
            
                        window.location.href = "http://localhost/trabalho/src/update.php?className=" + encodeURIComponent(currentData.classList[0]) + "&newValue=" + encodeURIComponent(newValue);
                    }

                    closeBtn.addEventListener('click', () => {
                        currentData.innerHTML = data;
                        editBtn.classList.remove("hide");
                        confirmBtn.classList.add("hide");
                        closeBtn.classList.add("hide");
                    });
                    
                    const inputs = document.querySelectorAll(".data input");
                    
                    inputs.forEach(input => {
                        input.addEventListener("keydown", e => {
                            if (e.key == "Enter") update();
                        });
                    });
                });
            });
        }, 50);

        feather.replace();
    </script>
</body>
</html>