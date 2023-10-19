<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);  

  /* 
    VOCE ATE PODERIA COLOCAR OPERACOES COMO LOGIN E TEXTOS DE
    CABECALHO EM FUNCOES EM UTIL.PHP MAS SAO ARQUIVOS QUE MUDAM DE
    PROJETO PRA PROJETO EM OPCOES E ESTRUTURA, ENTAO NAO COMPENSARIA
  */  
    
  // verifica se o login foi feito    
  if (isset($_SESSION['sessaoConectado'])) {
      $sessaoConectado = $_SESSION['sessaoConectado'];
  } else { 
      $sessaoConectado = false; 
  }

  // caso esteja logado ...
  if ( $sessaoConectado ) {
      /*
      aqui vc coloca opcoes de 
      - fechar o carrinho e pagar
      - opcoes de perfil do usuario
        1. forma de pagamentos padr�o por exemplo ...
        2. compras anteriores, etc
      */
      echo "<p align='right'><a href='logout.php'>Sair</a></p>";

      // caso seja administrador
      if ( $_SESSION['sessaoAdmin'] ) {
         echo "<p align='right'>Bom dia, Administrador<br>";
         /*
          aqui vc colocar opcoes de administracao
          - cadastro de produtos
          - cadastro de usuarios 
          ...
         */   
      // caso seja um usuario comum
      } else {   
        echo "<p align='right'>Bom dia, Fulano<br></p>";
        echo "<p align='right'><a href='carrinho.php'>Carrinho</a></p>"; 
      }
  // caso nao esteja logado    
  } else {
      /*
       aqui vc pode
       - ver o carrinho
       - procurar produtos
      */
      echo "<p align='right'><a href='frmUsuario.php'>Login</a></p>";
  }

  echo "<hr>";
?>
      