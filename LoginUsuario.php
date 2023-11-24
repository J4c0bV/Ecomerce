<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);  
  
  include("util.php");
  $conn = conecta();
  // inicia a sessao
  session_start();   

  // login que veio do form
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $eh_admin = false;

  $stringUrl = "../HOME/homepage.html";

  if ($email<>'') {
        
        if(funcaoLogin($email,$senha,$eh_admin) != false)
        {
          DefineCookie('loginCookie', $email, 60);
          $_SESSION['sessaoConectado'] = funcaoLogin($email,$senha,$eh_admin);
          $_SESSION['sessaoAdmin']     = $eh_admin;
        }else{
          $stringUrl = "frmLoginUsuario.php";
          echo '<script>';
          echo 'alert("Email ou senha inválidos! Redigite!");';
          echo '</script>';
        }
  }
  unset($insert);
  unset($conn);
  
  header('Location: HOME/homepage.html');
?> 