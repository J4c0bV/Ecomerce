<?php
  // mostra erros do php

  include("../util.php");

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

     $stringLogin= "
     <!DOCTYPE html>
     <html lang='pt-BR'>
     <head>
         <meta charset='UTF-8'>
         <meta http-equiv='X-UA-Compatible' content='IE=edge'>
         <meta name='viewport' content='width=device-width, initial-scale=1.0'>
         <title>Login</title>
         <link rel='stylesheet' type='text/css' href='login.css'>
         <link rel='stylesheet' type='text/css' href='stylePaginaUsuario.css'>
     </head>
     <body>
         <div class='pagina'>
             <form method='POST' action='LoginUsuario.php' class='formulario'>
                 <a href='../HOME/homepage.html' id='seta-btn'>
                     <img src='../imagens/seta.svg' alt='Seta'>
                 </a>
                 <h1>LOGIN</h1>
                 <p>Preencha os campos abaixo para fazer login na sua conta.</p>
                 <label for='email'>E-mail</label>
                 <input type='email' name='email' placeholder='Digite seu e-mail' value='$loginCookie'required />
                 <label for='senha'>Senha</label>
                 <input type='password' name='senha' placeholder='Digite sua senha' required />

                 <input type='hidden' name='verificacao' id='verificacao' required />
                 <button class='botao' type='submit'>Entrar</button>
                 <a href='../ESQUECI/frmEsqueciUsuario.php?acao=redefinirLogin' class= 'botao'>Esqueci a Senha</a>
                 <a href='../CADASTRO/frmCadastroUsuario.php' class='botao'>Cadastre-se</a>
                 <button class='botao' type='reset'>Apagar</button>
             </form>
         </div>
     </body>
     </html>
     ";
    
    /*$email = $_POST['email'];
    $senha = $_POST['senha'];
    if($email == null || $senha == null)
    {
    echo '<script>';
    echo 'alert("Email ou senha inválidos! Redigite!");';
    echo '</script>';
    }*/
     
  }else
  {
    $login = $_COOKIE['loginCookie'];
    
    $conn = conecta();
  
    $sql = "SELECT * FROM tbl_usuario WHERE email_usuario = '$login'";

    $select = $conn->query($sql);
 
    while($linha=$select->fetch())
    {
        $id  = $linha['id_usuario'];
        $nome=$linha['nome_usuario'];
        $endereco=$linha['rua_usuario'];
        $email =$linha['email_usuario'];
        $senha=$linha['senha_usuario'];
        $admin=$linha['admin_usuario'];
    }
    if($admin=='s')
    {
        $stringLogin=
        "
       <html lang='pt-br'>
       <head>
           <meta charset='UTF-8'>
           <meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate' />
           <meta http-equiv='Pragma' content='no-cache' />
           <meta http-equiv='Expires' content='0' />
           <title>Página do usuário</title>
           <link rel='stylesheet' type='text/css' href='stylePaginaUsuario.css'>
           <link rel='stylesheet' type='text/css' href='../HOME/styleCabecalhoRodape.css'>
       </head>
       <body>
           <div class='container'>
               <!-- InÃ­cio cabeÃ§alho -->
       
               <!--cabeÃ§alho logo, pesquisa, carrinho e login-->
               <div class = 'containerCabecalho'>
   
               <div class='cabecalhoPesquisa'>
    
                <a class='logoCabecalho' href='../HOME/homepage.html'><img width='200' src='../imagens/logo4.svg'></a>&nbsp;
    
                <form action='../PRODUTOS/produto_mostra.php'method='post' class='formCabecalho'>
                    <input type='text' name='valNome' id='pesquisa' class='imagensCabecalho'
                       size='40' maxlength='30' autofocus placeholder='Buscar' src='lupa.png' autocomplete='off'>&nbsp;
                </form>
    
                <a class='imagensCabecalho' href='../CARRINHO/carrinho.php'><img width='30' src='../imagens/carrinho.png'></a>&nbsp;
    
                <a class='imagensCabecalho' href='frmLoginUsuario.php'><img width='35' src='../imagens/user.png'></a>         
            </div>
            
            <!--cabeçalho sobre, produtos, devs-->
            <div class='cabecalho'>
                <a class='linksCabecalho' href='../SOBRE/sobre.html'><span class='animacaoHover'>&nbsp;Sobre</span></a>&nbsp;
                <a class='linksCabecalho' href='../PRODUTOS/produto_mostra.php'><span class='animacaoHover'>&nbsp;Produtos</span></a>&nbsp;
                <a class='linksCabecalho' href='../DEVS/paginaDesenvolvedores.html'><span class='animacaoHover'>&nbsp;Desenvolvedores</span></a>&nbsp;
            </div>
    
            </div>
               <!-- Fim cabeÃ§alho -->
       
               <div class='seuPerfil'>
                   <p> - Seu Perfil - Administrador - </p>
               </div>
       
               <!--little menu da esquerda-->
               <div class='menuPaginaUsuario'>
                   <fieldset>
                       <legend>Menu</legend>
                       <p>Nome: $nome
                           <br>
                           E-mail: $email</p> 
                           <br>
                           <br>
                           <a href='logout.php'><button class='botaoLogado' type='button'>Logout</button></a>
                           <br>
                           <br>
                           <a href='../CADASTRO_PRODUTO/crud.php'><button class='botaoLogado' type='button'>Acessar o CRUD Produtos</button></a>
                           <br>
                           <br>
                           <a href='../RELATORIO/frmRelatorio.php'><button class='botaoLogado' type='button'>Visualizar o relatório</button></a>
                   </fieldset>
               </div>
               
               <!--seguranÃ§a-->
               <div class='seguranca' name='seguranca'>
                   <fieldset>
                       <legend>Segurança</legend>
                       <p>Minha senha: &nbsp&nbsp&nbsp&nbsp</p>
                       <p id='senhaAsterisco'>$senha</p> 
                       <a href='../ESQUECI/frmEsqueciUsuario.php?login=$login&acao=recupera'><button class='botaoLogado' type='button'>Alterar a senha</button></a>
                   </fieldset>
               </div>
       
               <!--meu perfil-->
               <div class='meuPerfil' name='meuPerfil'>
                   <fieldset>
                       <legend>Meu Perfil</legend>
                       <p>Nome de usuário: </p>
                       <p>$nome</p><br>
                       <p>E-mail: </p>
                       <p>$email</p><br>
                       <p>Endereço: </p>
                       <p>$endereco </p>
                   </fieldset>
               </div>
       
               <!--InÃ­cio RodapÃ©-->
               <!--rodapÃ©-->
               <div class='rodape'>
               <a class='linksRodape' href='../HOME/homepage.html'>&nbsp;Home</a>
               <a class='linksRodape' href='../SOBRE/sobre.html'>&nbsp;Sobre</a> &nbsp;
               <a class='linksRodape' href='../PRODUTOS/produto_mostra.php'>&nbsp;Produtos</a> &nbsp;
               <a class='linksRodape' href='frmLoginUsuario.php'>&nbsp;Perfil</a> &nbsp;
           </div>
       
           <!--rodapÃ© devs-->
           <div class='rodapeDevs'>
               <center>
                   <p>Desenvolvedores
                       <br><br>
                           Jenyffer Butzke nº 16 - João Victor Bosi nº 17 - João Vitor Jacob nº 18 - João Vitor Lucio nº 19 - Laís Quintão nº 20
                       <br><br>
                   </p>
                   <a class='voltarTopo' href='#'>↑ Voltar ao topo</a>
               </center>
               
           </div>
       </body>
       </html>";
    }else
    {
        $stringLogin=
        "
       <html lang='pt-br'>
       <head>
           <meta charset='UTF-8'>
           <meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate' />
           <meta http-equiv='Pragma' content='no-cache' />
           <meta http-equiv='Expires' content='0' />
           <title>Página do usuário</title>
           <link rel='stylesheet' type='text/css' href='stylePaginaUsuario.css'>
           <link rel='stylesheet' type='text/css' href='../HOME/styleCabecalhoRodape.css'>
       </head>
       <body>
           <div class='container'>
               <!-- InÃ­cio cabeÃ§alho -->
       
               <!--cabeÃ§alho logo, pesquisa, carrinho e login-->
               <div class = 'containerCabecalho'>
   
               <div class='cabecalhoPesquisa'>
    
                <a class='logoCabecalho' href='../HOME/homepage.html'><img width='200' src='../imagens/logo4.svg'></a>&nbsp;
    
                <form action='../PRODUTOS/produto_mostra.php'method='post' class='formCabecalho'>
                    <input type='text' name='valNome' id='pesquisa' class='imagensCabecalho'
                       size='40' maxlength='30' autofocus placeholder='Buscar' src='lupa.png' autocomplete='off'>&nbsp;
                </form>
    
                <a class='imagensCabecalho' href='../CARRINHO/carrinho.php'><img width='30' src='../imagens/carrinho.png'></a>&nbsp;
    
                <a class='imagensCabecalho' href='frmLoginUsuario.php'><img width='35' src='../imagens/user.png'></a>         
            </div>
            
            <!--cabeçalho sobre, produtos, devs-->
            <div class='cabecalho'>
                <a class='linksCabecalho' href='sobre.html'><span class='animacaoHover'>&nbsp;Sobre</span></a>&nbsp;
                <a class='linksCabecalho' href='produto_mostra.php'><span class='animacaoHover'>&nbsp;Produtos</span></a>&nbsp;
                <a class='linksCabecalho' href='paginaDesenvolvedores.html'><span class='animacaoHover'>&nbsp;Desenvolvedores</span></a>&nbsp;
            </div>
    
            </div>
               <!-- Fim cabeÃ§alho -->
       
               <div class='seuPerfil'>
                   <p> - Seu Perfil - </p>
               </div>
       
               <!--little menu da esquerda-->
               <div class='menuPaginaUsuario'>
                   <fieldset>
                       <legend>Menu</legend>
                       <p>Nome: $nome
                           <br>
                           E-mail: $email</p> 
                           <br>
                           <a href='logout.php'><button class='botaoLogado' type='button'>Logout</button></a>
                   </fieldset>
               </div>
               
               <!--seguranÃ§a-->
               <div class='seguranca' name='seguranca'>
                   <fieldset>
                       <legend>Segurança</legend>
                       <p>Minha senha:</p>
                       <p id='senhaAsterisco'>$senha</p>
                       <a href='../ESQUECI/frmEsqueciUsuario.php?login=$login&acao=recupera'><button class='botaoLogado' type='button'>Alterar a senha</button></a>
                   </fieldset>
               </div>
       
               <!--meu perfil-->
               <div class='meuPerfil' name='meuPerfil'>
                   <fieldset>
                       <legend>Meu Perfil</legend>
                       <p>Nome de usuário: </p>
                       <p>$nome</p><br>
                       <p>E-mail: </p>
                       <p>$email</p><br>
                       <p>Endereço:</p>
                       <p>$endereco </p>
                   </fieldset>
               </div>
       
               <!--InÃ­cio RodapÃ©-->
               <!--rodapÃ©-->
               <div class='rodape'>
               <a class='linksRodape' href='homepage.html'>&nbsp;Home</a>
               <a class='linksRodape' href='sobre.html'>&nbsp;Sobre</a> &nbsp;
               <a class='linksRodape' href='produto_mostra.php'>&nbsp;Produtos</a> &nbsp;
               <a class='linksRodape' href='frmLoginUsuario.php'>&nbsp;Perfil</a> &nbsp;
           </div>
       
           <!--rodapÃ© devs-->
           <div class='rodapeDevs'>
               <center>
                   <p>Desenvolvedores
                       <br><br>
                           Jenyffer Butzke nº 16 - João Victor Bosi nº 17 - João Vitor Jacob nº 18 - João Vitor Lucio nº 19 - Laís Quintão nº 20
                       <br><br>
                   </p>
                   <a class='voltarTopo' href='#'>↑ Voltar ao topo</a>
               </center>
               
           </div>
       </body>
       </html>";
    }

    
  }
  echo $stringLogin;
?>

