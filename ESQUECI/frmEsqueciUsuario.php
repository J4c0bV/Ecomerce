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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if($acao == "recupera"){ ?>
        <h1>recuperação de senha</h1>
        <p>
            Este email: <?=$login?> é um que você tem acesso ?
            <br>
            Se não, digite um que você tem, se for o correto, apenas digite o seu nome.
        </p>
        <form action="esqueci.php" method="POST">
            <label for="email">Email para envio</label>
            <input type="text" id="email" name="emailEnviado" value="<?=$login ?>">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nomeEnviado">
            <input type="hidden" id="acao" name="acao" value="recupera">
            <input type="hidden" id="login" name="login" value="<?=$login ?>">
            <button type="submit">Enviar</button>
            
        </form>
    <?php }else if($acao == "envia"){?>
        <p>
            Digite a senha de recuperação enviado no seu email e a nova senha
        </p>

        <form action="esqueci.php" method="POST">
            <label for="email">Senha de recuperação</label>
            <input type="text" id="email" name="senhaRecupera">
            <label for="nome">Nova senha</label>
            <input type="text" id="nome" name="senhaNova">
            <input type="hidden" id="acao" name="acao"  value="envia">
        <button type="submit">Enviar</button>
        </form>
    <?php } ?>
</body>
</html>