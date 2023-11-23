<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);   
   
   include("../util.php");

   echo "
   <!DOCTYPE html>
   <html lang='pt-BR'>
   <head>
       <meta charset='UTF-8'>
       <meta http-equiv='X-UA-Compatible' content='IE=edge'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <title>Cadastro de Produtos</title>
       <link rel='stylesheet' type='text/css' href='crud.css'>
   </head>
   <body>
       <div class='conteudo'>
          <a href='../HOME/homepage.html' id='arrow-btn'>
            <img src='../imagens/seta.svg' alt='Seta'>
          </a>
          <center>
            <h1>CRUD DE PRODUTOS </h1>
          </center>";

   // faz conexao 
   $conn = conecta();
   
   $sql = "SELECT * FROM tbl_produto WHERE excluido_produto ='N' ORDER BY id_produto ASC";
   
   // faz um select basico
   $select = $conn->query($sql);
   
   // enquanto houver registro leia em $linha
   echo "<table border='1' id='tabela'>";
   echo " 
          <th>Id</th>
          <th>Preço</th>
          <th>Nome</th> 
          <th>Excluído</th>  
          <th>Custo</th> 
          <th>Margem de Lucro</th>
          <th>ICMS</th> 
          <th>Descrição</th>
          <th>Imagem</th>
          <th>Quantidade Disponível</th>
          <th>Alterar</th>
          <th>Excluir</th>";

   while ( $linha = $select->fetch() )  
   {
     // imprime as posicoes de $linha que sao os campos carregados  
     $varId = $linha['id_produto'];
     $varPreco = $linha['preco_produto'];
     $varNome = $linha['nome_produto'];
     $varExcluido = $linha['excluido_produto'];
     $varCusto = $linha['custo_produto'];
     $varMargem = $linha['margem_lucro_produto'];
     $varIcms = $linha['icms_produto'];
     $varDescricao= $linha['descricao_produto'];
     $varQntdDisponivel = $linha['quantidade_disponivel'];
     $varImagem= $linha['codigovisual_produto'];

     
     echo "<tr>"; 
     echo "
          <td><b>$varId</b></td>
          <td>R$ $varPreco</td>
          <td>$varNome</td>
          <td>$varExcluido</td>
          <td>R$ $varCusto</td>
          <td>R$ $varMargem</td>
          <td>R$ $varIcms</td>
          <td><p class = 'descricao'>$varDescricao</p></td>
          <td><img src='$varImagem'></td>
          <td>$varQntdDisponivel</td>
          <td><a href='formProduto.php?id=$varId'><img src='../imagens/editar.svg' style= 'width:50px'></a></td>
          <td><a href='delecao_produto.php?id=$varId'><img src='../imagens/excluir.svg' style= 'width:50px'></a></td>";
     echo "</tr>";   
        
   } 

   echo"</table>";

   $varId = '';

   echo"<a href=formProduto.php?id=$varId'><img src='../imagens/adicionar.svg' style= 'width:50px'></a>";

?>

