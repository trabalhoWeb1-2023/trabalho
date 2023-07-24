<?php
$servidor = "localhost"; $usuario = "root"; $senha = ""; $bd = "trabalho_web1_2023";

//criando a conexão usando os parâmetros acima
$conexao = mysqli_connect($servidor, $usuario, $senha,$bd);

//testando a conexão, se deu errado, para o código
if (!$conexao){
    die("A conexão falhou: ". mysqli_connect_error());
}
echo "Conectado com o banco.";
/* Como a conexão já foi realizada informando o banco ($bd), então, pode-se alterar as tabelas do banco diretamente. */

/*  Alteração para inclusão de um campo de e-mail.
    A ideia é incluir e-mails separados por vírgula. A validação deve ser realizada no código da aplicação para verificar se a string está bem formada. Será necessário criar uma expressão regular.
    O campo de e-mail não será alterado. 
    Esse novo campo poderá ser alterado, pode ficar nulo, mas precisa ficar com um formato válido: e-mails válidos em sua estrutura separados por vírgula.
*/
$_sql_alt = "ALTER TABLE `Respondente` ADD `novos_emails` VARCHAR(1000) NULL AFTER `email`;";
//executando a alteração e testando seu sucesso
if (mysqli_query($conexao,$_sql_alt)){
    echo "<br/> Sucesso na alteração da tabela para inclusão da coluna que possibilita o registro de novos e-mails.";
} else{
    echo ("<br/> Erro na alteração da tabela para inclusão da coluna que possibilita a inclusão de novos e-mails");
}

/* O tipo boolean no mysql "não existe". O banco usa o tinyint no lugar. Com o tinyint, o que for 0 é ‘false’ e o que for maior do que 0 é ‘true’. Normalmente, para indicar que algo é ‘true’, atribui-se o valor 1.
*/
$_sql_alt = "ALTER TABLE `Respondente` ADD `mudou_senha` BOOLEAN NOT NULL AFTER `novos_emails`;";
//executando a alteração e testando seu sucesso
if (mysqli_query($conexao,$_sql_alt)){
    echo ("<br/> Sucesso na alteração da tabela para inclusão campo de identificação primeiro login");
} else{
    echo ("<br/> Erro na alteração da tabela para inclusão campo de identificação primeiro login");
}

//Inicialmente, as senhas ficarão nulas e o banco aceitará. Mas só durante a alteração.
$_sql_alt = "ALTER TABLE `Respondente` ADD `senha` VARCHAR(100) NULL AFTER `mudou_senha`;";
/*A partir deste ponto, vou assumir que o acesso ao banco e meu script estão corretos. Então o acesso foi realizado com sucesso.*/
mysqli_query($conexao,$_sql_alt);

//preparando consulta para pegar os dados dos respondentes no banco
$_sql_consulta_respondentes = "select cpf,data_nasc from Respondente";
//pegando os dados
$retorno = mysqli_query($conexao,$_sql_consulta_respondentes);
/*verificando se retornou algum dado, pois a consulta foi realizada, mas pode não existir nada alinhado com o que foi pedido.*/
if(mysqli_num_rows($retorno) > 0){
    
    //criando sql base para inserção dos dados
    $_sql_senha_prepared_statment = "UPDATE `Respondente` SET senha=? WHERE cpf=?;";
    $stmt = $conexao->prepare($_sql_senha_prepared_statment);    
    $stmt->bind_param("ss",$nova_senha,$cpf);
    //realizando o bind com os dados que estavam no banco e retornaram na consulta.
    while($tupla = mysqli_fetch_assoc($retorno)){        
        $nova_senha = $tupla["data_nasc"]; //data nasc associada a um registro do BD
        $cpf = $tupla["cpf"]; //cpf do usuário associado a um registro no BD
        $stmt->execute();        
    }
    $stmt->close();
    echo ("<br/> Inclusão de senhas temporárias realizada com sucesso.");

} else{    
    echo ("<br/> Erro na inclusão das senhas temporárias.");
}  

//deixando a senha obrigatória daqui pra frente. Senha vazia é diferente de campo nulo.
$_sql_alt = "ALTER TABLE `Respondente` MODIFY `senha` varchar(100) NOT NULL";
mysqli_query($conexao,$_sql_alt);

echo " <br/> Encerrando conexão com o banco";
mysqli_close($conexao);

?>

