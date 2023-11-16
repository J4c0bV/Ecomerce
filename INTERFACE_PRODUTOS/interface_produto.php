<?php
include("../util.php");

echo"<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <title>Interface Produto</title>
    <link rel='stylesheet' type='text/css' href='interfaceProduto.css'>
    <link rel='stylesheet' type='text/css' href='../HOME/styleCabecalhoRodape.css'>
    <script src='jsInterfaceProduto.js' defer></script>

</head>
<body>

   
    <div class='container'> 


        <div class = 'containerCabecalho'>

           <div class='cabecalhoPesquisa'>

            <a class='logoCabecalho' href='../HOME/homepage.html'><img width='200' src='../imagens/logo4.svg'></a>&nbsp;

            <form action='../PRODUTOS/produto_mostra.php' class='formCabecalho'method='POST'>
                    <input type='text' name='valNome' id='pesquisa' class='imagensCabecalho'
                    size='40' maxlength='30' autofocus placeholder='Buscar' src='../imagens/lupa.png' autocomplete='off'>&nbsp;
            </form>

            <a class='imagensCabecalho' href='../CARRINHO/carrinho.php'><img width='30' src='../imagens/carrinho.png'></a>&nbsp;

            <a class='imagensCabecalho' href='../LOGIN/frmLoginUsuario.php'><img width='35' src='../imagens/user.png'></a>         
        </div>
        
        <!--cabeçalho sobre, produtos, devs-->
        <div class='cabecalho'>
            <a class='linksCabecalho' href='../SOBRE/sobre.html'><span class='animacaoHover'>&nbsp;Sobre</span></a>&nbsp;
            <a class='linksCabecalho' href='../PRODUTOS/produto_mostra.php'><span class='animacaoHover'>&nbsp;Produtos</span></a>&nbsp;
            <a class='linksCabecalho' href='../DEVS/paginaDesenvolvedores.html'><span class='animacaoHover'>&nbsp;Desenvolvedores</span></a>&nbsp;
        </div>

        </div>
";
$conn = conecta();

$id = $_GET['id']; 
  
$sql = "SELECT * FROM tbl_produto WHERE id_produto=$id and excluido_produto = 'N' ";

// faz um select basico
$select = $conn->query($sql)->fetch();
    $preco = $select['preco_produto'];
    $nome = $select['nome_produto']; 
    $custo = $select['custo_produto'];
    $margem = $select['margem_lucro_produto'];
    $icms = $select['icms_produto'];
    $descricao = $select['descricao_produto'];
    $qntdDisponivel = $select['quantidade_disponivel'];
    $imagem = $select['codigovisual_produto'];

    

    while($qntdDisponivel<0)
    {
        $sql3= "UPDATE tbl_produto SET quantidade_disponivel= 0 WHERE id_produto=$id";

        $conn -> query($sql3);
        $qntdDisponivel = $select['quantidade_disponivel'];
    }
 

 echo"

<div class = 'geral'>

 <div class = 'parteBranca'>

     <div class = 'superior'>

         <div class = 'esquerda'>

             <div class = 'imgPrincipal'>
                            <img src = '$imagem' class = 'principal'>
             </div>

         </div>

         <div class = 'direita'>
             <div  class = 'nomeProduto'> $nome</div>

             <div class = 'estrelas'> 
                 <div><img src = '../imagens/estrela.png'> </div>
                 <div><img src = '../imagens/estrela.png'> </div>
                 <div><img src = '../imagens/estrela.png'> </div>
                 <div><img src = '../imagens/estrela.png'> </div>
                 <div><img src = '../imagens/estrela.png'> </div>
             </div>

             <div class = 'precoDesconto'> R$20,00</div>

             <div class = 'precoProduto'>R$$preco,00</div>

             <form action = '../CARRINHO/carrinho.php?operacao=incluir&idProduto=$id' class='formQuantidade' method='post'> 

             <div class = 'selecionarQuantidade' >

                 <div class = 'selecionarQunatidadeTxt1'> Quantidade: &nbsp;</div>
             
                 <input type='number' id='quantidade' name='quantidadeProduto' min='1' max='5' placeholder='0'>  

                 <div class = 'selecionarQunatidadeTxt2'> &nbsp;&nbsp;($qntdDisponivel disponíveis)</div>

             </div>";
            
            if($qntdDisponivel>0){
             echo"
             <div class = 'botoes'>
                <a href='../CARRINHO/carrinho.php?operacao=incluir&idProduto=$id'><button class = 'btnCarrinho' onclick= 'btnCarrinho()'>Adicionar ao carrinho</button></a> 
                </div>
             </form>
             ";
            }else
            {
                echo"
             <div class = 'botoes'>
                <a href='../CARRINHO/carrinho.php?operacao=incluir&idProduto=$id'><button class = 'btnCarrinho' onclick= 'btnCarrinho()'>Adicionar ao carrinho</button></a>
                </div>
             </form>
                ";
            }
             
             echo"
         
         </div>
     </div>

     <hr>

     <div class = 'meio'>

         <div class = 'titulo'> Descrição</div>

         <div class = 'descricao'>$descricao</div>
     </div>
 </div>

 <div class = 'inferior'> 
 <div class = 'titulo'>Outros produtos</div>
 <div class = 'linhaProdutos'>
 ";

 
 $sql2 = "SELECT * FROM tbl_produto WHERE id_produto!=$id and excluido_produto = 'N' ";

 $select2 = $conn->query($sql2);
 while($linha = $select2->fetch())
 {
        $id2= $linha['id_produto'];
        $preco2 = $linha['preco_produto'];
        $nome2 = $linha['nome_produto']; 
        $custo2 = $linha['custo_produto'];
        $margem2 = $linha['margem_lucro_produto'];
        $icms2 = $linha['icms_produto'];
        $descricao2 = $linha['descricao_produto'];
        $qntdDisponivel2 = $linha['quantidade_disponivel'];
        $imagem2 = $linha['codigovisual_produto'];

        echo"

        

         
         <div class = 'quadradoProduto'>
         <a href='interface_produto.php?id=$id2'><img src = '$imagem2'></a>
             <hr>
             <div class = 'infoQuadradoProduto'>
                
                 <div class = 'tituloOutroProduto'>$nome2</div>
                 <div class = 'precoOutroDesconto'>R$20,00</div>
                 <div class = 'precoOutroProduto'>R$$preco2,00</div>
             </div>
         </div>

        

        ";
    }

echo"
</div>

</div>

</div>

    <div class='rodape'>
        <a class='linksRodape' href='../HOME/homepage.html'>&nbsp;Home</a>
        <a class='linksRodape' href='../SOBRE/sobre.html'>&nbsp;Sobre</a> &nbsp;
        <a class='linksRodape' href='../PRODUTOS/produto_mostra.php'>&nbsp;Produtos</a> &nbsp;
        <a class='linksRodape' href='../LOGIN/frmLoginUsuario.php'>&nbsp;Perfil</a> &nbsp;
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
    



?>