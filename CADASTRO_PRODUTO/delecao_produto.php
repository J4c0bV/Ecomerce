<?php
include("../util.php");
 
$conn = conecta();

$linha = [ 'id_produto' => $_GET['id'] ]; 


$update = $conn->prepare("UPDATE tbl_produto SET excluido_produto= 's' WHERE id_produto=:id_produto");
$update->execute($linha);



header('Location: ../CRUD/crud.php');  
?>