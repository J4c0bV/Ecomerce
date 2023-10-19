<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  // se nao estiver conectado vai pedir o login
  if (isset($_SESSION['sessaoConectado'])) {
      $sessaoConectado = $_SESSION['sessaoConectado'];
  } else { 
    $sessaoConectado = false; 
  }

  // se sessao nao conectada ...
  if (!$sessaoConectado) { 
     
     $loginCookie = '';

     // recupera o valor do cookie com o usuario    
     if (isset($_COOKIE['loginCookie'])) {
        $loginCookie = $_COOKIE['loginCookie']; 
     }

     $stringLogin= "
     <!DOCTYPE html>
     <html lang='pt-BR'>
     <head>
         <meta charset='UTF-8'>
         <meta http-equiv='X-UA-Compatible' content='IE=edge'>
         <meta name='viewport' content='width=device-width, initial-scale=1.0'>
         <title>Login</title>
         <link rel='stylesheet' type='text/css' href='style.css'>
     </head>
     <body>
         <div class='pagina'>
             <form method='POST' action='LoginUsuario.php' class='formulario'>
                 <a href='testeHomePage.html' id='seta-btn'>
                     <img src='seta.svg' alt='Seta'>
                 </a>
                 <h1>LOGIN</h1>
                 <p>Preencha os campos abaixo para fazer login na sua conta.</p>
                 <label for='email'>E-mail</label>
                 <input type='email' name='email' placeholder='Digite seu e-mail' value='$loginCookie'required />
                 <label for='senha'>Senha</label>
                 <input type='password' name='senha' placeholder='Digite sua senha' required />
                 <label for='confirmarSenha'>Confirmar Senha</label>
                 <input type='password' name='confirmarSenha' placeholder='Confirme sua senha' required/><br>
                 <button class='botao' type='submit'>Entrar</button>
                 <a href='frmCadastroUsuario.php' class='botao'>Cadastre-se</a>
                 <button class='botao' type='reset'>Apagar</button>
             </form>
         </div>
     </body>
     </html>
     ";
     echo $stringLogin;
  }
?>

