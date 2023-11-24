<?php
include("../util.php");

session_start();

if(isset($_GET['login'])){
    $login = $_GET['login'];
}else{
    $login = "";
}

if(isset($_GET['acao'])){
    $acao = $_GET['acao'];
}else{
    $acao = "";
}

echo "
<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Recuperação de Senha</title>
    <link rel='stylesheet' type='text/css' href='esqueciSenha.css'>

</head>
<body>";

if($acao == 'recupera')
{
    echo "
    <div class = 'pagina'>

    <form action='../ESQUECI/esqueci.php' method='POST'>

        <h1>ALTERAÇÃO DE SENHA</h1>
        <p>
            Este email: $login é um que você tem acesso?
            <br>
            Se não, digite um que você tem, se for o correto, apenas digite o seu nome.
        </p>

        <label for='email'>Email para envio</label>
        <input type='text' id='email' name='emailEnviado' value='$login'>

        <label for='nome'>Nome</label>
        <input type='text' id='nome' name='nomeEnviado'>

        <input type='hidden' id='acao' name='acao' value='recupera'>

        <input type='hidden' id='login' name='login' value='$login'>

        <button type='submit'>Enviar</button>
    </form>
    </div>";

} else if($acao == 'envia')
{
    echo "
    <div class = 'pagina'>

    <form action='../ESQUECI/esqueci.php' method='POST'>

        <p>
            Digite a senha de recuperação enviado no seu email e a nova senha.
        </p>

        <label for='email'>Senha de recuperação</label>
        <input type='text' id='email' name='senhaRecupera'>

        <label for='nome'>Nova senha</label>
        <input type='text' id='nome' name='senhaNova'>

        <input type='hidden' id='acao' name='acao'  value='envia'>

        <button type='submit'>Enviar</button>
    </form>
    </div>";
} else if($acao == 'redefinirLogin'){
    echo "
    <div class = 'pagina'>

    <form action='../ESQUECI/esqueci.php' method='POST'>
        <p>
            Digite o email da sua conta, e lembresse de ter acesso a ele
        </p>

        <label for='email'>Email para acesso</label>
        <input type='text' id='email' name='emailEnviado'>

        <label for='nome'>Nome</label>
        <input type='text' id='nome' name='nomeEnviado'>

        <input type='hidden' id='acao' name='acao'  value='redefinirLogin'>

        <button type='submit'>Enviar</button>
    </form>
    </div>";
}

echo "
</body>
</html>";

?>