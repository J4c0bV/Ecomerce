<?php 
   // mostra erros do php

   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("../util.php");
 
   $conn = conecta();
     
   $preco = doubleval($_POST['preco_produto']);
   $nome = $_POST['nome_produto']; 
   $excluido = $_POST['excluido_produto'];
   $custo = intval($_POST['custo_produto']);
   $icms = intval($_POST['icms_produto']);
   $margem = $preco - ($custo+$icms);
   $descricao = $_POST['descricao_produto'];
   $qntdDisponivel = intval($_POST['quantidade_disponivel']);
   $imagem = $_POST ['codigovisual_produto'];

   $linha = [ 'preco_produto'=>$preco,'nome_produto'=> $nome, 'excluido_produto'=> $excluido,
               'custo_produto'=>$custo, 'margem_lucro_produto' => $margem, 'icms_produto' => $icms, 'cogidovisual_produto' => $imagem, 
               'descricao_produto'=>$descricao,'quantidade_disponivel'=> $qntdDisponivel];
   
   $insert = $conn->prepare("INSERT INTO tbl_produto(preco_produto,nome_produto,excluido_produto,
   custo_produto, margem_lucro_produto, icms_produto, codigovisual_produto, descricao_produto, quantidade_disponivel) 
   VALUES(:preco_produto, ':nome_produto', ':excluido_produto', :custo_produto, :margem_lucro_produto, :icms_produto, ':codigovisual_produto', 
   ':descricao_produto', :quantidade_disponivel)");
      
   $insert->execute($linha);
   header('Location: ../CRUD/crud.php');    
?>