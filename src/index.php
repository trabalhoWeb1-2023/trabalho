<!-- 
    FALTA AQUI

    - máscara para cpf no form de enviar o email
    - questões do cadastro, validação e inserção dos dados
    - bug do alert que restou aqui
 -->

<?php
include("connection.php");
include("session.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <h1>Início</h1>

    <form action="sendEmail.php" method="post">
        <label for="cpf">CPF</label>
        <input type="text" minlength="11" maxlength="11" name="cpf" />
        <button type="submit">Avançar</button>
        <button type="button" id="registerModalBtn">Cadastre-se</button>
    </form>
    <dialog>
        <form action="register.php" id="registerForm" method="post">
            <i data-feather="x" onclick="closeModal()"></i>
            <h3>Cadastro</h3>
            <div class="input-wrapper">
                <label for="name">Nome completo:</label>
                <input type="text" required name="name" id="name" />
            </div>
            <div class="input-wrapper">
                <label for="cpf">CPF:</label>
                <input type="number" required name="cpf" id="cpf" />
            </div>
            <div class="input-wrapper">
                <label for="birthdate">Data de nascimento:</label>
                <input type="date" required name="birthdate" id="birthdate" />
            </div>
            <div class="input-wrapper">
                <label for="weight">Peso (kg):</label> <!-- questão da virgula passar pra ponto -->
                <input type="number" required name="weight" id="weight" />
            </div>
            <div class="input-wrapper">
                <label for="height">Altura (m):</label>
                <input type="number" required name="height" id="height" />
            </div>
            <div class="input-wrapper">
                <label for="hours_of_sleep">Horas de sono por dia:</label>
                <input type="number" required name="hours_of_sleep" id="hours_of_sleep" />
            </div>
            <div class="input-wrapper">
                <label for="userEmail">E-mail:</label>
                <input type="email" required name="userEmail" id="userEmail" />
            </div>
            <button type="submit" id="registerButton" class="button">Cadastrar</button>
        </form>
    </dialog>

    <script>
        const registerModalBtn = document.querySelector("#registerModalBtn");
        const registerModal = document.querySelector("dialog");

        let message = <?php echo isset($_SESSION['message']) ? json_encode($_SESSION['message']) : json_encode(""); ?>;

        registerModalBtn.addEventListener("click", () => {
            registerModal.showModal();
        });

        if (message != "") {
            setTimeout(() => {
                alert(message);
            }, 100);
        }

        function closeModal() {
            document.querySelector("dialog").close();
        }

        feather.replace();
    </script>
</body>

</html>