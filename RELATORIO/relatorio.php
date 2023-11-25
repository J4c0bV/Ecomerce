<?php
ini_set ( 'display_errors' , 1); 
error_reporting (E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);   

include('../util.php');

$conn = conecta();

if(isset($_POST['dInicial']) &&
    isset($_POST['dFinal']) &&
    isset($_POST['acao']))
{
    $dataInicial = $_POST['dInicial'];
    $dataFinal = $_POST['dFinal'];

    $acao = $_POST['acao'];
            
    $SQLCompra = 
        "SELECT tbl_compra.id_compra, tbl_compra.data, tbl_usuario.nome_usuario, 
            SUM(tbl_compra_produto.quantidade * tbl_produto.preco_produto) AS total 
        FROM tbl_compra 
            INNER JOIN tbl_usuario ON tbl_compra.fk_usuario = tbl_usuario.id_usuario 
            INNER JOIN tbl_compra_produto ON tbl_compra_produto.fk_compra = tbl_compra.id_compra 
            INNER JOIN tbl_produto ON tbl_produto.id_produto = tbl_compra_produto.fk_produto 
        WHERE 
            tbl_compra.data >= :datai and tbl_compra.data <= :dataf  AND tbl_compra.status_pedido = 'Concluida' 
        GROUP BY 
            tbl_compra.id_compra, tbl_compra.data, tbl_usuario.nome_usuario 
        ORDER BY 
            tbl_compra.data"; 

        $SQLItensCompra = 
            "SELECT tbl_produto.nome_produto, tbl_compra_produto.quantidade, tbl_produto.preco_produto, 
            tbl_compra_produto.quantidade * tbl_produto.preco_produto subtotal 
            FROM tbl_compra_produto  
            INNER JOIN tbl_produto ON tbl_produto.id_produto = tbl_compra_produto.fk_produto 
            WHERE 
            tbl_compra_produto.fk_compra = :fk_compra
            ORDER BY tbl_produto.descricao_produto"; 


        setlocale(LC_ALL, 'pt_BR.utf-8', );

    $html = "<html> ";
        // abre a consulta de COMPRA do periodo
    $compra = $conn->prepare($SQLCompra);
    $compra->execute ( [ 'datai' => $dataInicial, 
                    'dataf' => $dataFinal ] );
        // prepara os ITENS     
    $itens_compra = $conn->prepare($SQLItensCompra);

    $html .= "
    <br>
    <table border = 1>";

    while ( $linha_compra = $compra->fetch() )  
        {
            $cod_compra = sprintf('%03s',$linha_compra['id_compra']);
            $data       = sprintf('%12s',$linha_compra['data']);
            $cliente    = sprintf('%50s',$linha_compra['nome_usuario']);
            $total      = sprintf('%10s',number_format($linha_compra['total'], 2, ',', '.'));

            $html .= "
            <tr>
                <th class='cod'>Cod</th>
                <th class='cod'>Data</th>
                <th class='cod'>Cliente</th>
                <th class='cod'>$ Total</th>
            </tr>
            <tr>
                <td>$cod_compra</td>
                <td>$data</td>
                <td>$cliente</td>
                <td>$total</td>
            </tr>
            ";               
            
            // executa ITENS passando o codigo da COMPRA atual
            $itens_compra->execute( [ 'fk_compra' => 
                                $linha_compra['id_compra'] ] );
            $html .= "
            <tr>
                <th class='Produto'>Produto</th>
                <th class='Produto'>Qt</th>
                <th class='Produto'>Unit</th>
                <th class='Produto'>Sub</th>
            </tr>
            ";

            while ( $linha_itens_compra = $itens_compra->fetch() ) 
            {
                $produto  = sprintf('%20s',$linha_itens_compra['nome_produto']);
                $qtd      = sprintf('%5s',$linha_itens_compra['quantidade']);
                $unit     = sprintf('%10s',number_format($linha_itens_compra['preco_produto'], 2, ',', '.'));
                $subtotal = sprintf('%10s',number_format($linha_itens_compra['subtotal'], 2, ',', '.'));

                $html .= "
                <tr>
                    <td>$produto</td>
                    <td>$qtd</td>
                    <td>$unit</td>
                    <td>$subtotal</td>
                </tr>";
            }
        }

        $html .= "</table></div></html>";
        $url = "frmRelatorio.php?html="."&head=".urlencode($head);

    if($acao == 'GeraPdf'){
        echo $html;
        ?>
        <head>
        <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Relatório</title>
            <style>
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-family: Arial, sans-serif; /* Adicionando a fonte Arial */
                }

                th, td {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }

                th {
                    
                }

                .Produto{
                    background-color: #e0f3a9;
                }

                .cod {
                    background-color: #b7cc7a;
                }

                form{
                    display: block;
                }
            </style>
        </head>
        <form action="frmRelatorio.php" >
            <label>Gostaria de voltar para ver mais relatorios?</label>
            <button type="submit">Clique aqui!</button>
        </form>

        <?php
    }else if($acao = 'baixar'){
    $url = "frmRelatorio.php";
    if ( CriaPDF ( 'Relatorio de Vendas', $html, 'relatorios/relatorio.pdf' ) )  
    {
        echo 'Gerado com sucesso';
    }
    header(("Location: $url"));
    }
}
?>
