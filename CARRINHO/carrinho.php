<?php

 // visualizar todos os erros
    /*error_reporting(E_ALL);
    ini_set("display_errors", 1);*/

    // iniciar sessao
    session_start();

    // incluir util.php
    include ("../util.php");

    // captura session_id (garante o acesso concorrente)
    $session_id = session_id();  
    $produtos = array();

    // chama conecta() e guarda a conexao default em $conn
    $conn = conecta();   // conecta com o banco e obtem a var de controle $conecta
    $stringCarrinhoEstrutura = "
    <!DOCTYPE html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'>
        <title>Carrinho</title>
        <link rel='stylesheet' type='text/css' href='carrinho.css'>
        <script src='jsInterfaceProduto.js' defer></script>
    </head>
    <body>
    ";
    $login = $_COOKIE['loginCookie'];
    var_dump($login);
    if($login <>""){
        $login = $_COOKIE['loginCookie'];
        $codigoUsuario = ValorSQL($conn, " select id_usuario from tbl_usuario 
                                        where email_usuario = '$login'");
    }
    // se estiver logado pega o codigo do usuario atraves do $login 
    // if ( isset($_COOKIE['loginCookie']) ) {
    //     $login = $_COOKIE['loginCookie'];
    //     $codigoUsuario = ValorSQL($conn, " select id_usuario from tbl_usuario 
    //                                     where email_usuario = '$login'");
    // } 
    /*else { 
        echo '<script>';
        echo 'alert("Não é possível fazer compras sem estar logado!");';
        echo '</script>';
        header('Location: ../HOME/homepage.html');
    }*/
        
    // existe alguma compra associada ao session_id ??
    $existe = intval ( ValorSQL($conn," select count(*) from tbl_compra inner join tbl_compra_temporaria
                                        on tbl_compra.id_compra = tbl_compra_temporaria.fk_compra  
                                        where tbl_compra_temporaria.id_sessao = '$session_id' ") ) == 1;
    // se nao existe
    if (!$existe) {   
        
        $dataHoje = date('Y/m/d');
    
        $statusCompra = 'Pendente';

        // cria um registro de compras com o usuario nulo
        ExecutaSQL($conn," insert into tbl_compra (data, status_pedido) 
                        values ('$dataHoje','$statusCompra') ");

        // recupera o codigo usado no auto-incremento
        $codigoCompra = $conn->lastInsertId();

        // insere o tmpcompra
        ExecutaSQL($conn," insert into tbl_compra_temporaria (id_sessao, fk_compra) 
                        values ('$session_id',$codigoCompra) ");  
    
    } else {

        // como o teste mostrou que ja existe um registro de compra
        // retorna esse codigo de compra
        $codigoCompra = intval ( ValorSQL($conn," select id_compra from tbl_compra
                                                inner join tbl_compra_temporaria on tbl_compra.id_compra = 
                                                tbl_compra_temporaria.fk_compra 
                                                where tbl_compra_temporaria.id_sessao = '$session_id' "));

        // obtem o status dessa compra, se criou agora, entao eh 'pendente'
        $statusCompra = ValorSQL($conn, " select status_pedido from tbl_compra 
                                        where id_compra = $codigoCompra ");
            
    } 

    ////////////// se estiver logado atualiza e 'liga' a compra com o 
    ////////////// usuario

    if (isset($codigoUsuario)) {
        ExecutaSQL($conn,"update tbl_compra 
                            set fk_usuario = $codigoUsuario 
                        where 
                            fk_usuario is null and 
                            id_compra = $codigoCompra"); 
    }

    // se o carrinho foi chamado por COMPRAR, EXCLUIR ou FECHAR

    if ($_GET) { 
        
        $operacao      = $_GET['operacao'];
        $codigoProduto = $_GET['idProduto'];
        var_dump($codigoProduto);
        var_dump($_POST['quantidadeProduto']);
        if($_POST['quantidadeProduto']<>""){
            $qntdProdutoInicial = $_POST['quantidadeProduto'];
        }else{
            $qntdProdutoInicial = 1;
        }
        

        // obtem a qtd atual desse produto no carrinho  
        $quantidade = intval ( ValorSQL($conn," select quantidade 
                                                from tbl_compra_produto 
                                                where 
                                                fk_produto = $codigoProduto and 
                                                fk_compra = $codigoCompra ") );
        
        if ($operacao == 'incluir') {
            if ($quantidade == 0) {
                ExecutaSQL($conn,
                        " insert into tbl_compra_produto 
                            (quantidade,fk_produto,fk_compra) 
                            values (
                            $qntdProdutoInicial,$codigoProduto,$codigoCompra) "); 
            } else{
                ExecutaSQL($conn,
                        " update tbl_compra_produto 
                            set quantidade = quantidade + 1 
                            where 
                            fk_produto = $codigoProduto and 
                            fk_compra = $codigoCompra "); 
                
                        
            }
        } else 
        if ($operacao == 'excluir') {
              
            if ($quantidade <= 1) { 
                ExecutaSQL($conn," delete from 
                                    tbl_compra_produto 
                                where 
                                    fk_produto = $codigoProduto and 
                                    fk_compra = $codigoCompra ");         
            } else {
                ExecutaSQL($conn," update tbl_compra_produto 
                                    set quantidade = quantidade - 1 
                                where 
                                    fk_produto = $codigoProduto and 
                                    fk_compra = $codigoCompra ");       
            }
        } else 
        if ($operacao == 'fechar') {
        echo "<br> >> Vamor fechar...<br>";  
        // muda o status da compra pra concluida
        // faz um form pra colocar forma de pagamento
        // colocar opcao de pix, cartao, etc, etc
        // conforme orientacao da professora jovita, 
        // exclua fisicamente o tmpcompra referente a essa compra
        // ...   

        $produtoFechado = $_GET['produtoFechado'];
        $produtos = json_decode(urldecode($produtoFechado), true);
        foreach($produtos as $produto_id => $quantidade){
            $qntdRetirada = $produtos[$produto_id];
            ExecutaSQL($conn, "update tbl_produto set quantidade_disponivel = quantidade_disponivel - $qntdRetirada 
                       where id_produto = $produto_id"); 
        }
         

        $statusCompra = 'Concluida';
       ExecutaSQL($conn," update tbl_compra 
                            set status_pedido = '$statusCompra'
                          where
                            id_compra = $codigoCompra");

       ExecutaSQL($conn," delete from 
                                  tbl_compra_temporaria
                               where 
                                  fk_compra = $codigoCompra");
        }

        $stringCarrinhoEstrutura.="
        <script>
        var newURL = location.href.split('?')[0];
        window.history.pushState('object', document.title, newURL)
        window.setTimeout(function() {
            $('.alert').fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
                window.location.reload();
            });
        }, 4000);
        </script>";

    } 
    
    $stringCarrinhoEstrutura .= "
    <div class='container'> 


        <div class = 'containerCabecalho'>

            <div class='cabecalhoPesquisa'>

                <a class='logoCabecalho' href='../HOME/homepage.html'> <img width='200' src='../imagens/logo4.svg'></a>&nbsp;

                <form action='../PRODUTOS/produto_mostra.php' class='formCabecalho' method='POST'>
                    <input type='text' name='valNome' id='pesquisa' class='imagensCabecalho'
                    size='40' maxlength='30' autofocus placeholder='Buscar' src='../imagens/lupa.png' autocomplete='off'>&nbsp;
                </form>

                <a class='imagensCabecalho' href='carrinho.php'><img width='30' src='../imagens/carrinho.png'></a>&nbsp;

                <a class='imagensCabecalho' href='../LOGIN/frmLoginUsuario.php'><img width='35' src='../imagens/user.png'></a>         
            </div>

            <div class='cabecalho'>
                <a class='linksCabecalho' href='../SOBRE/sobre.html'><span class='animacaoHover'>&nbsp;Sobre</span></a>&nbsp;
                <a class='linksCabecalho' href='../PRODUTOS/produto_mostra.php'><span class='animacaoHover'>&nbsp;Produtos</span></a>&nbsp;
                <a class='linksCabecalho' href='../DEVS/paginaDesenvolvedores.html'><span class='animacaoHover'>&nbsp;Desenvolvedores</span></a>&nbsp;
            </div>

        </div>


        <div class = 'corpo'>
 ";
    
    // faz a selecao pra montar a tabela
    $sql = " SELECT
                tbl_produto.id_produto,
                tbl_produto.codigovisual_produto,
                tbl_produto.nome_produto,
                tbl_produto.descricao_produto AS descprod,
                tbl_compra_produto.quantidade,
                tbl_produto.preco_produto,
                tbl_produto.preco_produto * tbl_compra_produto.quantidade AS sub
            FROM tbl_produto
                INNER JOIN tbl_compra_produto ON tbl_produto.id_produto = tbl_compra_produto.fk_produto
                INNER JOIN tbl_compra ON tbl_compra.id_compra = tbl_compra_produto.fk_compra
                WHERE
                    tbl_compra_produto.fk_compra = $codigoCompra 
                    AND tbl_compra.status_pedido = 'Pendente'
                ORDER BY
                    tbl_produto.descricao_produto;";
    $select = $conn->query($sql);
    
    // cria table com itens no carrinho e seus subtotais
    while ( $linha = $select->fetch() ) {
        $codigoProduto = $linha['id_produto']; 
        $nomeProduto   = $linha['nome_produto'];
        $imagem        = $linha['codigovisual_produto'];
        $descProd      = $linha['descprod'];
        $quant         = $linha['quantidade'];
        $vunit         = $linha['preco_produto'];
        $sub           = $linha['sub'];

        // vc poderia incluir links para 'incluir' alem dos 'excluir'
        // com isso, o usuario nao precisaria voltar na home pra incrementar 
        // a quantidade do mesmo produto

        if (array_key_exists($produto_id, $produtos)) {
            $produtos[$codigoProduto] += $quant;
        } else {
            $produtos[$codigoProduto] = $quant;
        }
        $stringCarrinhoEstrutura .= "
        
        <div class = 'produto'>
        <img class = 'imagemProduto' src='$imagem'>

        <div class = 'infoProduto'>

            <div class = 'nomeProduto'>$nomeProduto</div>

            <div class = 'selecionarQuantidade'>

                <div>Quantidade: $quant</div>
                <div class='quantidade-container'>
                            <a href='carrinho.php?operacao=incluir&idProduto=$codigoProduto'><button class='btnAdicionar' onclick= 'btnCarrinho()'>+</button> </a>    
                            <a href='carrinho.php?operacao=excluir&idProduto=$codigoProduto'><button class='btnSubtrair' onclick='btnExcluir()'>-</button></a>    
                </div>

            </div>

            <div class = 'precoUnitario'>Preço Unitário: R$ $vunit,00</div>
            <div class = 'precoTodosItens'>Subtotal: R$ $sub,00</div>
            <button class = 'btnExcluir' onclick='btnExcluir()'><a href='carrinho.php?operacao=excluir&idProduto=$codigoProduto'>Excluir</a></button>

        </div>
    </div>
      
      ";    
    }
    // calcula o total e mostra junto com o status da compra 
    $total = ValorSQL($conn," select sum (tbl_produto.preco_produto * tbl_compra_produto.quantidade)  
    from tbl_produto 
            inner join tbl_compra_produto on 
            tbl_produto.id_produto = tbl_compra_produto.fk_produto
            inner join tbl_compra on tbl_compra.id_compra = tbl_compra_produto.fk_compra                       
    where tbl_compra_produto.fk_compra = $codigoCompra AND tbl_compra.status_pedido = 'Pendente'"); 
    
    if($total <>""){

        //echo "Status da compra: $statusCompra<br>";
        //echo "Total: $total <br><br>";
        $produtos_serializados = json_encode($produtos);
        $stringCarrinhoEstrutura .= "
        <div class = 'total'>TOTAL: R$ $total,00</div>
        <button class = 'btnComprar' onclick = 'btnComprar()'><a href='carrinho.php?operacao=fechar&produtoFechado=$produtos_serializados&idProduto=$codigoProduto'>Comprar</a></button>
        </div>";
    }else{
       $stringCarrinhoEstrutura.="<p class='mensagem'>Adicione produtos para poder comprar!</p>";
    }
    
    $stringCarrinhoEstrutura .= "
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
    
</div>
</body>
</html>";
 
echo $stringCarrinhoEstrutura;

    // se o login foi obtido (se esta logado), mostra link 'fechar carrinho' 
    if ( isset($login) ) 
    {
    if ($statusCompra == 'Pendente' && $login <> '') {
//        echo "<a href='carrinho.php?operacao=fechar&idProduto=0'>Fechar o carrinho</a>";         
    }
    }
     

?>