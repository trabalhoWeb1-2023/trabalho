<?php
    session_start();
    include("connection.php");
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
        if (strlen($cpf) != 11 ||
            $cpf == 00000000000 ||
            $cpf == 11111111111 ||
            $cpf == 22222222222 ||
            $cpf == 33333333333 ||
            $cpf == 44444444444 ||
            $cpf == 55555555555 ||
            $cpf == 66666666666 ||
            $cpf == 77777777777 ||
            $cpf == 88888888888 ||
            $cpf == 99999999999) {
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
?>