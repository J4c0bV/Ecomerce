<?php

//Inicio HTML
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL);   

include("../util.php");

echo "
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <title>Produtos</title>
    <link rel='stylesheet' type='text/css' href='../PRODUTOS/produtos.css'>
    

</head>
<body>

    <div class='container'> 

    <div class='cabecalhoPesquisa'>

    <a class='logoCabecalho' href='../HOME/homepage.html'><img width='200' src='../imagens/logo4.svg'></a>&nbsp;

    <form action='produto_mostra.php' class='formCabecalho' method='POST'>
        <input type='text' name='valNome' id='pesquisa' class='imagensCabecalho'
        size='40' maxlength='30' autofocus placeholder='Buscar' src='../imagens/lupa.png' autocomplete='off'>&nbsp;
    </form>

    <a class='imagensCabecalho' href='../CARRINHO/carrinho.php'><img width='30' src='../imagens/carrinho.png'></a>&nbsp;

    <a class='imagensCabecalho' href='../LOGIN/frmLoginUsuario.php'><img width='35' src='../imagens/user.png'></a>         
</div>

<!--cabeçalho sobre, produtos, devs-->
<div class='cabecalho'>
    <a class='linksCabecalho' href='../SOBRE/sobre.html'><span class='animacaoHover'>&nbsp;Sobre</span></a>&nbsp;
    <a class='linksCabecalho' href='produto_mostra.php'><span class='animacaoHover'>&nbsp;Produtos</span></a>&nbsp;
    <a class='linksCabecalho' href='../DEVS/paginaDesenvolvedores.html'><span class='animacaoHover'>&nbsp;Desenvolvedores</span></a>&nbsp;
</div>

";

//Mostrar todos os produtos disponiveis

$qtdProdutos = 0;

$conn = conecta();
if (isset($_POST['valNome'])) {
    $valNome = $_POST['valNome'];
} else {
    $valNome = "";
}

$sql = " select * from tbl_produto 
         where (nome_produto like '%$valNome%')   
         order by nome_produto ";
// faz um select basico
$select = $conn->query($sql);

echo "<div class = 'corpo'>";

while($linha = $select->fetch())
{
    if($qtdProdutos == 0 )
    {
        echo "<div class = 'linhaProdutos'>";
    }

    $varId  = $linha['id_produto'];
    $varPreco = $linha['preco_produto'];
    $varNome = $linha['nome_produto'];
    $varImagem = $linha['codigovisual_produto'];
    
    echo "
    <div class='produtos'>
        
    <a href='../INTERFACE_PRODUTOS/interface_produto.php?id=$varId'><img class = 'imgProduto' src='$varImagem'></a>
                
    <hr>
            <div class = 'infoProduto'>
                <div class = 'tituloProduto'>$varNome</div>
                <div class = 'precoDescontoProduto'>R$20,00</div>
                <div class = 'precoProduto'>R$$varPreco,00</div>
            </div>
    </div>
    ";

    $qtdProdutos++;

    if($qtdProdutos == 2)
    {
        echo "</div>";
        $qtdProdutos = 0;
    }
}



echo "</div>";

//Fim
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
