<?php

 // visualizar todos os erros
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    // iniciar sessao
    session_start();

    // incluir util.php
    include ("util.php");

    // captura session_id (garante o acesso concorrente)
    $session_id = session_id();  

    // chama conecta() e guarda a conexao default em $conn
    $conn = conecta();   // conecta com o banco e obtem a var de controle $conecta

    // se estiver logado pega o codigo do usuario atraves do $login 
    if ( isset($_SESSION['sessaoLogin']) ) {
        $login = $_SESSION['sessaoLogin'];
        $codigoUsuario = ValorSQL($conn, " select id_usuario from tbl_usuario 
                                        where email_usuario = '$login'");
    }
        
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
                            set fk_id_usuario = $codigoUsuario 
                        where 
                            fk_id_usuario is null and 
                            id_compra = $codigoCompra"); 
    }

    // se o carrinho foi chamado por COMPRAR, EXCLUIR ou FECHAR

    if ($_GET) { 
        
        $operacao      = $_GET['operacao'];
        $codigoProduto = $_GET['idProduto'];

        // obtem a qtd atual desse produto no carrinho  
        $quantidade = intval ( ValorSQL($conn," select quantidade 
                                                from tbl_compra_produto 
                                                where 
                                                fk_produto = $codigoProduto and 
                                                fk_compra = $codigoCompra ") );  
        if ($operacao == 'incluir') {
            echo "<br> >> Vamor incluir...<br>";
            if ($quantidade == 0) {
                echo $codigoCompra;
                ExecutaSQL($conn,
                        " insert into tbl_compra_produto 
                            (quantidade,fk_produto,fk_compra) 
                            values (1,$codigoProduto,$codigoCompra) "); 
            } else {
                ExecutaSQL($conn,
                        " update tbl_compra_produto 
                            set quantidade = quantidade + 1 
                            where 
                            fk_produto = $codigoProduto and 
                            fk_compra = $codigoCompra "); 
                        
            }
        } else 
        if ($operacao == 'excluir') {
            echo "<br> >> Vamor excluir...<br>";     
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
        }
    } 
    
    echo "<br><strong>Compras ateh o momento...</strong><br>
    
        <table border='1'>
            <tr>
            <td>Produto</td>
            <td>Qtd</td>
            <td>$ unit</td>
            <td>$ sub</td>
            <td></td>
            </tr>";
    
    // faz a selecao pra montar a tabela
    $sql = " select tbl_produto.id_produto, 
                    tbl_produto.descricao_produto as descprod, 
                    tbl_compra_produto.quantidade, 
                    tbl_produto.preco_produto, 
                    tbl_produto.preco_produto * tbl_compra_produto.quantidade as sub  
            from tbl_produto
                inner join tbl_compra_produto on 
                    tbl_produto.id_produto = tbl_compra_produto.fk_produto 
            where tbl_compra_produto.fk_compra = $codigoCompra  
            order by tbl_produto.descricao_produto ";
    $select = $conn->query($sql);
    
    // cria table com itens no carrinho e seus subtotais
    while ( $linha = $select->fetch() ) {
        
        $codigoProduto = $linha['id_produto']; 
        $descProd      = $linha['descprod'];
        $quant         = $linha['quantidade'];
        $vunit         = $linha['preco_produto'];
        $sub           = $linha['sub'];

        // vc poderia incluir links para 'incluir' alem dos 'excluir'
        // com isso, o usuario nao precisaria voltar na home pra incrementar 
        // a quantidade do mesmo produto
        
        echo "<tr>
                <td>$descProd</td>
                <td>$quant</td>
                <td>$vunit</td>
                <td>$sub</td>
                <td><a href='carrinho.php?operacao=excluir&idProduto=$codigoProduto'>Excluir</a></td>
                </tr>";    
    }
    
    echo "</table>";
    
    // calcula o total e mostra junto com o status da compra     
    $total = ValorSQL($conn," select sum (tbl_produto.preco_produto * tbl_compra_produto.quantidade)  
                            from tbl_produto 
                                    inner join tbl_compra_produto on 
                                    tbl_produto.id_produto = tbl_compra_produto.fk_produto                           
                            where tbl_compra_produto.fk_compra = $codigoCompra "); 

    echo "Status da compra: $statusCompra<br>";
    echo "Total: $total <br><br>";
    
    // se o login foi obtido (se esta logado), mostra link 'fechar carrinho' 
    if ( isset($login) ) 
    {
    if ($statusCompra == 'Pendente' && $login <> '') {
        echo "<a href='carrinho.php?operacao=fechar&idProduto=0'>Fechar o carrinho</a>";         
    }
    }

    // link pra voltar pra home
    echo "<br>
        <a href='index.php'>Home</a>";   
    
 
?>