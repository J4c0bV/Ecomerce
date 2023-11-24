<?php 
   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);

   include("../util.php");
 
   $conn = conecta();

   echo "
   <!DOCTYPE html>
   <html lang='pt-BR'>
   <head>
       <meta charset='UTF-8'>
       <meta http-equiv='X-UA-Compatible' content='IE=edge'>
       <meta name='viewport' content='width=device-width, initial-scale=1.0'>
       <title>Cadastro de Produtos</title>
       <link rel='stylesheet' type='text/css' href='cadprod.css'>
   </head>
   <body>
       <div class='pagina'>";
     
   if (isset($_GET['id'])) {
       $id = $_GET['id']; 
   } else {
       $id = ""; 
   }
                    
   if($id <> "")
   {

   $sql = "SELECT * FROM tbl_produto WHERE id_produto=$id";
     
   
   // faz um select basico
   $select = $conn->query($sql)->fetch();
   $IncluiOuAtualiza = "salvar_produto.php";

   
    $preco = $select['preco_produto'];
    $nome = $select['nome_produto']; 
    $excluido = $select['excluido_produto'];
    $custo = $select['custo_produto'];
    $margem = $select['margem_lucro_produto'];
    $icms = $select['icms_produto'];
    $descricao = $select['descricao_produto'];
    $qntdDisponivel = $select['quantidade_disponivel'];
    $imagem =  $select['codigovisual_produto'];

   
   }
   else
   {
    
    $IncluiOuAtualiza = "insercao_produto.php";

    $id = NULL;
    $excluido = NULL;
    $preco = "";
    $nome = "";
    $excluido = "N";
    $custo = "";
    $margem = "";
    $icms = "";
    $descricao="";
    $qntdDisponivel = "";
    $imagem = "";

   
   }
   $varHTML = "

   <form method='POST' action='$IncluiOuAtualiza' class='formulario' enctype='multipart/form-data'>

        <a href='crud.php' id='arrow-btn'>
            <img src='../imagens/seta.svg' alt='Seta'>
        </a>

        <center>
           <h1>CADASTRO DE PRODUTOS</h1>
        </center>
        
        <input type='hidden' name='id_produto' value='$id'>

        <input type='hidden' name='excluido_produto' value='$excluido'>

        <label for='nome_produto'>Nome do Produto</label>
        <input type='text' name='nome_produto' placeholder='Digite o nome do produto' required value='$nome'/>

        <label for='categoriaProduto'>Categoria</label>
        <select name='categoriaProduto' required>
            <option value='Informática'>Informática</option>
            <option value='Mecânica'>Mecânica</option>
            <option value='Eletrônica'>Eletrônica</option>
        </select>

        <label for='preco_produto'> Preço (R$)</label>
        <input type='text' name='preco_produto' required value='$preco' />

        <label for='quantidade_disponivel'>Quantidade</label>
        <input type='text' name='quantidade_disponivel' required value='$qntdDisponivel' />

        <label for='custo_produto'>Custo (R$)</label>
        <input type='text' name='custo_produto' required value='$custo'>

        <label for='icms_produto'>ICMS (R$)</label>
        <input type='text' name='icms_produto' required value='$icms'>

        <label for='descricao_produto'>Descrição do Produto</label>
        <input type='text' name='descricao_produto' rows='4' placeholder='Digite a descrição do produto' required value='$descricao'>

        <label for='codigovisual_produto'>Imagem do Produto</label>
        <input type='text' name='codigovisual_produto' placeholder='Digite o nome da imagem' required value='$imagem'/>

        <button class='botao' type='submit'>Cadastrar Produto</button>
        <button class='botao' type='reset'>Limpar</button>

    </form>
     "; 
     
  echo $varHTML;

  echo "</div>
        </body>
        </html>";  
?>
