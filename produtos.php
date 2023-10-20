<?php

//Inicio HTML

echo "

<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <title>HomePage</title>
    <link rel='stylesheet' type='text/css' href='produtos.css'>
</head>
<body>

    <!-- div geral -->
    <div class='container'> 

        <!-- Início cabeçalho -->

        <div class = 'containerCabecalho'>

            <!--cabeçalho logo, pesquisa, carrinho e login-->
            <div class='cabecalhoPesquisa'>

                <a class='logoCabecalho' href='testeHomePage.html'> <img width='200' src='E-commerce/logo4.svg'></a>&nbsp;

                <form action='' class='formCabecalho'> 

                    <input type='text' name='pesquisa' id='pesquisa' class='imagensCabecalho'
                       size='40' maxlength='30' autofocus placeholder='Buscar' src='imagens/lupa.png' autocomplete='off'>&nbsp;

                </form>

                <a class='imagensCabecalho' href='testeCarrinho.html'><img width='30' src='imagens/carrinho.png'></a>&nbsp;

                <a class='imagensCabecalho' href=''><img width='35' src='imagens/user.png'></a>         
            </div>

            <!--cabeçalho sobre, produtos, devs-->
            <div class='cabecalho'>
                <a class='linksCabecalho' href='testeSobre.html'><span class='animacaoHover'>&nbsp;Sobre</span></a>&nbsp;
                <a class='linksCabecalho' href='testeHomePage.html'><span class='animacaoHover'>&nbsp;Produtos</span></a>&nbsp;
                <a class='linksCabecalho' href='testeDesenvolvedores.html'><span class='animacaoHover'>&nbsp;Desenvolvedores</span></a>&nbsp;
            </div>

        </div>

        <!-- Fim cabeçalho -->
";

//Mostrar todos os produtos disponiveis

$qtdProdutos = 0;

echo "<div class = 'corpo'>"

//while($linha = $select->fetch())
while($linha = $select->fetch())
{
    if($NumeroDeProdutos == 0 )
    {
        echo "<div class = 'linhaProdutos'>";
    }

    $varNome = $linha['nome_produto'];
    $varPreco = $linha['preco_produto'];

    echo "
    <div class='produtos'>

        <img class = 'imgProduto' src='$imagemProduto'>
                
            <div class = 'infoProduto'>
                <div class = 'tituloProduto'>$nomeProduto</div>
                <div class = 'precoDescontoProduto'>R$20,00</div>
                <div class = 'precoProduto'>$precoProduto</div>
            </div>
    </div>
    ";

    $qtdprodutos++;

    if($qtdProdutos == 2)
    {
        echo "</div>";
        $qtdProdutos = 0;
    }
}

if($qtdProdutos != 0)
{
        echo "</div>";
}

echo "</div>";

//Fim

?>