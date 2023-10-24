<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  // se nao estiver conectado vai pedir o login
  session_start(); 
  // se nao estiver conectado vai pedir o login
  if (isset($_SESSION['sessaoConectado'])) {
    $sessaoConectado = $_SESSION['sessaoConectado'];
      
  } else { 
    $sessaoConectado = false; 
  }

  
  // se sessao nao conectada ...
  if ($sessaoConectado==false) { 
     
     $loginCookie = '';

     // recupera o valor do cookie com o usuario    

     $stringFormsUsuario = "<!DOCTYPE html>
     <html lang='pt-BR'>
     <head>
         <meta charset='UTF-8'>
         <meta http-equiv='X-UA-Compatible' content='IE=edge'>
         <meta name='viewport' content='width=device-width, initial-scale=1.0'>
         <title>Cadastro</title>
         <link rel='stylesheet' type='text/css' href='cadastro.css'>
     </head>
     <body>
         <div class='page'>
             <form method='POST' action='CadastroUsuario.php' class='formCadastro'>
                 <a href='../LOGIN/frmLoginUsuario.php' id='arrow-btn'>
                     <img src='../imagens/seta.svg' alt='Seta'>
                 </a>
     
                 <h1>CADASTRE-SE</h1>
                 <p>Preencha os campos abaixo para criar uma conta.</p>
                 
                 <label for='name'>Nome</label>
                 <input type='text' name='nome' placeholder='Digite seu nome' autofocus='true' required />
                 
                 <label for='email'>E-mail</label>
                 <input type='email' name='email' value='$loginCookie' placeholder='Digite seu e-mail' required />
                 
                 <label for='telefone'>Telefone</label>
                 <input type='text' name='telefone' placeholder='Digite seu telefone' required />

                 <label for='cpf'>CPF</label>
                 <input type='text' name='cpf' placeholder='Digite seu CPF' required />
                 
                 <label for='cep'>CEP</label>
                 <input type='text' name='cep' placeholder='Digite seu CEP' required />
                 <label for='estado'>Estado</label>
                 <input type='text' name='estado' placeholder='Estado' required />
                 <label for='cidade'>Cidade</label>
                 <input type='text' name='cidade' placeholder='Cidade' required />
                 <label for='rua'>Rua</label>
                 <input type='text' name='rua' placeholder='Rua' required />

                 <label for='password'>Senha</label>
                 <input type='password' name='senha' placeholder='Digite sua senha' required />

                 <label for='confirmPassword'>Confirmar Senha</label>
                 <input type='password' name='confirmPassword' placeholder='Confirme sua senha' required/><br>
                 <input type='submit' value='Cadastrar' class='btn' />
                 
                 <input type='reset' value='Apagar' class='btn' />
             </form>
         </div>
     </body>
     </html>
     
     ";
     
     echo $stringFormsUsuario;
  }
?>

