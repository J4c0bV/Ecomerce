<?php 
   // mostra erros do php

   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("../util.php");
 
   $conn = conecta();

   $linha = [
      'preco_produto'=>$_POST['preco_produto'], 
      'nome_produto'=> $_POST['nome_produto'], 
      'excluido_produto'=>$_POST['excluido_produto'],
      'custo_produto'=>$_POST['custo_produto'], 
      'margem_lucro_produto' =>$_POST['preco_produto']-($_POST['custo_produto']+$_POST['icms_produto']),
      'icms_produto' => $_POST['icms_produto'], 
      'codigovisual_produto'=> $_POST['codigovisual_produto'],
      'descricao_produto' => $_POST['descricao_produto'], 
      'quantidade_disponivel'=> $_POST['quantidade_disponivel']
      
  ];


   $sql = "INSERT INTO tbl_produto 
   (preco_produto, nome_produto, excluido_produto,custo_produto,margem_lucro_produto,icms_produto,codigovisual_produto,descricao_produto, quantidade_disponivel) 
   values (:preco_produto, :nome_produto,  :excluido_produto, :custo_produto, :margem_lucro_produto, :icms_produto, 
   :codigovisual_produto,:descricao_produto, :quantidade_disponivel )"; 

   $insert = $conn->prepare($sql); 
      
   $insert->execute($linha);
   header('Location: crud.php');    
?>