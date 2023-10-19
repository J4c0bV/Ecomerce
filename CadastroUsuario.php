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

  $linha = [ 'nome'  => $_POST['nome'],
             'cpf' => $_POST['cpf'],
             'email' => $_POST['email'],
             'telefone' => $_POST['telefone'],
             'cep' => $_POST['cep'],
             'estado' => $_POST['estado'],
             'cidade' => $_POST['cidade'],
             'rua' => $_POST['rua'],
             'senha' => $_POST['senha'],
             'admin' => 'n'
            ];

  if ($email<>'') {
        DefineCookie('loginCookie', $email, 60); 
        $_SESSION['sessaoConectado'] = true; 
        $_SESSION['sessaoAdmin']     = $eh_admin; 
        
        $CadastroSql = "INSERT INTO tbl_usuario (nome_usuario, cpf_usuario, email_usuario, telefone_usuario, cep_usuario, estado_usuario, cidade_usuario, rua_usuario, senha_usuario, admin_usuario)
            VALUES (:nome, :cpf, :email, :telefone, :cep, :estado, :cidade, :rua, :senha, :admin)";
        $insert = $conn->prepare($CadastroSql); 
        $insert->execute($linha);
  }
  unset($insert);
  unset($conn);
  //tem como mandar por get informações 
  header('Location: index.php');
?> 