<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);  
  
  include("../util.php");
  $conn = conecta();
  // inicia a sessao
  session_start();   

  // login que veio do form
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $eh_admin = false;

  if ($email<>'') {
        DefineCookie('loginCookie', $email, 60);
        echo $senha;
        $_SESSION['sessaoConectado'] = funcaoLogin($email,$senha,$eh_admin); 
        echo "sessao ".$_SESSION['sessaoConectado'];
        $_SESSION['sessaoAdmin']     = $eh_admin; 

  }
  unset($insert);
  unset($conn);
  
  header('Location: ../HOME/homepage.html');
?> 