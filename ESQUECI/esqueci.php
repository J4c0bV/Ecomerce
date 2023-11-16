<?php
include('../util.php');
$conn = conecta();
$acao = $_POST["acao"];

  if($acao == 'recupera'){
    if(isset($_POST['emailEnviado'])){
        $login = $_POST['login'];
        var_dump($login);
        $email = $_POST['emailEnviado'];
        $nome  = $_POST['nomeEnviado'];
    
        $senhaRecupera = GeraSenha();
    
        ExecutaSQL($conn, "update tbl_usuario SET senha_usuario = '$senhaRecupera' where email_usuario = '$login'");
    
        EnviaEmail($email, $nome,$senhaRecupera);

        header('Location: frmEsqueciUsuario.php?acao=envia');
    
      }  
  }else if ($acao == 'envia'){
    $senhaRecupera = $_POST['senhaRecupera'];
    $senhaNova     = $_POST['senhaNova'];

    ExecutaSQL($conn, "update tbl_usuario SET senha_usuario = '$senhaNova' where senha_usuario = '$senhaRecupera'");

    header('Location: ../LOGIN/frmLoginUsuario.php');

  } else if($acao == 'redefinirLogin'){
    if(isset($_POST['emailEnviado'])){
      $email = $_POST['emailEnviado'];
      $nome  = $_POST['nomeEnviado'];
  
      $senhaRecupera = GeraSenha();
  
      ExecutaSQL($conn, "update tbl_usuario SET senha_usuario = '$senhaRecupera' where email_usuario = '$email'");
  
      EnviaEmail($email, $nome,$senhaRecupera);

      header('Location: frmEsqueciUsuario.php?acao=envia');
  
    } 
  }
?>