<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("../util.php");

   $conn = conecta();

   $linha = [
            'id_produto'=>$_POST['id_produto'], 
            'preco_produto'=>$_POST['preco_produto'], 
            'nome_produto'=> $_POST['nome_produto'], 
            'excluido_produto'=>$_POST['excluido_produto'],
            'custo_produto'=>$_POST['custo_produto'], 
            'icms_produto' => $_POST['icms_produto'], 
            'margem_lucro_produto' =>$_POST['preco_produto']-($_POST['custo_produto']+$_POST['icms_produto']),
            'descricao_produto' => $_POST['descricao_produto'], 
            'quantidade_disponivel'=> $_POST['quantidade_disponivel'],
            'codigovisual_produto'=> $_POST['codigovisual_produto']
        ];


   $sql = "UPDATE tbl_produto SET preco_produto=:preco_produto, nome_produto=:nome_produto, excluido_produto=:excluido_produto, 
   custo_produto=:custo_produto, margem_lucro_produto=:margem_lucro_produto, icms_produto=:icms_produto, 
   descricao_produto=:descricao_produto, quantidade_disponivel=:quantidade_disponivel, codigovisual_produto=:codigovisual_produto
   WHERE id_produto=:id_produto"; 

   $update = $conn->prepare($sql); 
   $update->execute($linha);


   header('Location: crud.php');      

?>
