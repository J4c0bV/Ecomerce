<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  // inicia a sessao
  session_start(); 
  $_SESSION['sessaoConectado']=false; 
  $_SESSION['sessaoAdmin']=false; 

  DefineCookie('loginCookie', $email, 0);
  header('Location: ../HOME/homepage.html');
?>