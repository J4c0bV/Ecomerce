<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);   

   // inicia a sessao
   session_start(); 
   
   include("util.php");

   echo "<html>";   
   
   // seu css
   echo "<head>
         <link rel='stylesheet' type='text/css' 
          href='nome_do_seu_css.css'>
         <script></script> 
         </head>";

   echo "<body>";
    //echo session_id();
   include ('cabecalho.php');

   // faz conexao 
   $conn = conecta(); 
   
   /* 
   
    aqui vc coloca td que tera na homepage 
    
   */

  /////////////////////////////////////////////////////////////// 
  //////////GRIDE DE PRODUTOS 
  /////////////////////////////////////////////////////////////// 
  $select = $conn->query(" select * from tbl_produto
                           where excluido_produto ='n' 
                           order by descricao_produto ");

  $card = 0;
  echo "<table border='1'>";
  
  while ($linha = $select->fetch()) {
      $idProduto        = $linha['id_produto'];
      $descricaoProduto = $linha['descricao_produto'];
      $precoProduto     = $linha['preco_produto'];
      $imagemProduto    = $linha['codigovisual_produto'];
  
      if ($card == 0) { // nova linha !!
          echo "<tr>";      
      } 

      $card++;
      echo "<td>
             <center> 
              <img src='$imagemProduto' width=50><br>
              <strong>$descricaoProduto</strong><br>
              <i>$precoProduto</i><br>
              <a href='carrinho.php?operacao=incluir&idProduto=$idProduto'>Comprar</a>
             </center>
            </td>";  

      if ($card == 4) { // fecha linha
          $card = 0;
          echo "</tr>"; 
      }
  }
  echo "</table>";
 ////////////////////////////////////////////////////////////////////

  echo "</body></html>";
?>