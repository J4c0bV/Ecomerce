<?php 

   // mostra erros do php
   ini_set ( 'display_errors' , 1); 
   error_reporting (E_ALL);   
   
   include("util.php");

  echo "
  <html lang='en'>
  <head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Relatorio</title>
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
        background-color: #f2f2f2;
      }

      .Produto{
        background-color: #87CEFA;
      }

    </style>
  </head>
  <body>

  ";
    

   // calcula hoje
   $hoje = date('Y-m-d');
   // calcula ontem
   $ontem = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $hoje ) ) ));
  
   echo "

   <div class='container'>
    <form action='' method='POST' class='custom-form'>
      <label for='datai'>Data inicial</label>
      <input type='date' name='datai' id='datai' value='" . (isset($_POST['datai']) ? $_POST['datai'] : $ontem) . "'><br>

      <label for='dataf'>Data final</label>
      <input type='date' name='dataf' id='dataf' value='" . (isset($_POST['dataf']) ? $_POST['dataf'] : $ontem) . "'><br>

      <input type='submit' name='gerar' value='Gerar Relatório' class='custom-button'>
      <input type='submit' name='baixar' value='Baixar   ' class='custom-button button'>
        <br>
        <br>
    <input type='submit' name='expandir' value='Expandir' class='custom-button button'>
      </form>

  </div>";

   if ( $_POST ) {

      $datai = $_POST['datai'];
      $dataf = $_POST['dataf'];

      if (isset($_POST['gerar'])) {
        $conn = conecta();

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
                 tbl_compra.data;"; 
     
         $SQLItensCompra = 
                   "select tbl_produto.nome_produto, tbl_compra_produto.quantidade, tbl_produto.preco_produto, 
                   tbl_compra_produto.quantidade * tbl_produto.preco_produto subtotal 
                 from tbl_compra_produto  
                   inner join tbl_produto on tbl_produto.id_produto = tbl_compra_produto.fk_produto 
                 where 
                   tbl_compra_produto.fk_compra = :fk_compra
                 order by tbl_produto.descricao_produto"; 
      
        /*   m o d e l o
        Cod  Data        Cliente               $ Total
          1  20/10/2023  JOAO DA SILVA        10000,00
            Produto      Qt   Unit        Sub
            CHAVEIRO      2   50,00    100,00
            BOTOM         1   10,00     10,00
        */
     
        // formata valores em reais 
        setlocale(LC_ALL, 'pt_BR.utf-8', );
     
        $html = "<html> ";
     
        // abre a consulta de COMPRA do periodo
        $compra = $conn->prepare($SQLCompra);
        $compra->execute ( [ 'datai' => $datai, 
                            'dataf' => $dataf ] );
        // prepara os ITENS     
        $itens_compra = $conn->prepare($SQLItensCompra);
     
        $html .= "
          <table>";
        
        // fetch significa carregar proxima linha
        // qdo nao tiver mais nenhuma retorna FALSE pro while
      
        /////////////  M E S T R E ////////////////////   
        // carrega a proxima linha COMPRA
     
        while ( $linha_compra = $compra->fetch() )  
        {
          $cod_compra = sprintf('%03s',$linha_compra['id_compra']);
          $data       = sprintf('%12s',$linha_compra['data']);
          $cliente    = sprintf('%50s',$linha_compra['nome_usuario']);
          $total      = sprintf('%10s',number_format($linha_compra['total'], 2, ',', '.'));
          
          $html .= "
          <tr>
            <th>Cod</th>
            <th>Data</th>
            <th>Cliente</th>
            <th>$ Total</th>
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
     
          /////////////  D E T A L H E  ////////////////////
          // carrega a proxima linha ITENS_COMPRA
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
              </tr>
              ";
          }
     
        }
     
        $html .= "</table></div></html>";
      echo $html;
  }
      if (isset($_POST['baixar'])) {
   // faz conexao 
   $conn = conecta();

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
                 tbl_compra.data;"; 
     
         $SQLItensCompra = 
                   "select tbl_produto.nome_produto, tbl_compra_produto.quantidade, tbl_produto.preco_produto, 
                   tbl_compra_produto.quantidade * tbl_produto.preco_produto subtotal 
                 from tbl_compra_produto  
                   inner join tbl_produto on tbl_produto.id_produto = tbl_compra_produto.fk_produto 
                 where 
                   tbl_compra_produto.fk_compra = :fk_compra
                 order by tbl_produto.descricao_produto"; 
      
        /*   m o d e l o
        Cod  Data        Cliente               $ Total
          1  20/10/2023  JOAO DA SILVA        10000,00
            Produto      Qt   Unit        Sub
            CHAVEIRO      2   50,00    100,00
            BOTOM         1   10,00     10,00
        */
     
        // formata valores em reais 
        setlocale(LC_ALL, 'pt_BR.utf-8', );
     
        $html = "<html> ";
     
        // abre a consulta de COMPRA do periodo
        $compra = $conn->prepare($SQLCompra);
        $compra->execute ( [ 'datai' => $datai, 
                            'dataf' => $dataf ] );
        // prepara os ITENS     
        $itens_compra = $conn->prepare($SQLItensCompra);
     
        $html .= "
          <table>";
        
        // fetch significa carregar proxima linha
        // qdo nao tiver mais nenhuma retorna FALSE pro while
      
        /////////////  M E S T R E ////////////////////   
        // carrega a proxima linha COMPRA
     
        while ( $linha_compra = $compra->fetch() )  
        {
          $cod_compra = sprintf('%03s',$linha_compra['id_compra']);
          $data       = sprintf('%12s',$linha_compra['data']);
          $cliente    = sprintf('%50s',$linha_compra['nome_usuario']);
          $total      = sprintf('%10s',number_format($linha_compra['total'], 2, ',', '.'));
          
          $html .= "
          <tr>
            <th>Cod</th>
            <th>Data</th>
            <th>Cliente</th>
            <th>$ Total</th>
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
     
          /////////////  D E T A L H E  ////////////////////
          // carrega a proxima linha ITENS_COMPRA
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
              </tr>
              ";
          }
     
        }
     
        $html .= "</table></div></html>";
  
  if ( CriaPDF ( 'Relatorio de Vendas', 
                 $html, 
                'relatorios/relatorio.pdf' ) )  {
   echo 'Gerado com sucesso';
 }

   //header('Location: relatorios/relatorio.pdf');
  
   }
      if (isset($_POST['expandir'])) {
    // faz conexao 
    $conn = conecta();

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
                 tbl_compra.data;"; 
     
         $SQLItensCompra = 
                   "select tbl_produto.nome_produto, tbl_compra_produto.quantidade, tbl_produto.preco_produto, 
                   tbl_compra_produto.quantidade * tbl_produto.preco_produto subtotal 
                 from tbl_compra_produto  
                   inner join tbl_produto on tbl_produto.id_produto = tbl_compra_produto.fk_produto 
                 where 
                   tbl_compra_produto.fk_compra = :fk_compra
                 order by tbl_produto.descricao_produto"; 
      
        /*   m o d e l o
        Cod  Data        Cliente               $ Total
          1  20/10/2023  JOAO DA SILVA        10000,00
            Produto      Qt   Unit        Sub
            CHAVEIRO      2   50,00    100,00
            BOTOM         1   10,00     10,00
        */
     
        // formata valores em reais 
        setlocale(LC_ALL, 'pt_BR.utf-8', );
     
        $html = "<html> ";
     
        // abre a consulta de COMPRA do periodo
        $compra = $conn->prepare($SQLCompra);
        $compra->execute ( [ 'datai' => $datai, 
                            'dataf' => $dataf ] );
        // prepara os ITENS     
        $itens_compra = $conn->prepare($SQLItensCompra);
     
        $html .= "
          <table>";
        
        // fetch significa carregar proxima linha
        // qdo nao tiver mais nenhuma retorna FALSE pro while
      
        /////////////  M E S T R E ////////////////////   
        // carrega a proxima linha COMPRA
     
        while ( $linha_compra = $compra->fetch() )  
        {
          $cod_compra = sprintf('%03s',$linha_compra['id_compra']);
          $data       = sprintf('%12s',$linha_compra['data']);
          $cliente    = sprintf('%50s',$linha_compra['nome_usuario']);
          $total      = sprintf('%10s',number_format($linha_compra['total'], 2, ',', '.'));
          
          $html .= "
          <tr>
            <th>Cod</th>
            <th>Data</th>
            <th>Cliente</th>
            <th>$ Total</th>
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
     
          /////////////  D E T A L H E  ////////////////////
          // carrega a proxima linha ITENS_COMPRA
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
              </tr>
              ";
          }
     
        }
     
        $html .= "</table></div></html>";
       if ( CriaPDFF ( 'Relatório de Vendas', 
                       $html, 
                       'relatorios/relatorio.pdf' ) )  {
                         header('Content-Type: application/pdf');
                         header('Content-Disposition: attachment; filename="relatorio.pdf"');
                         header('Content-Length: ' . filesize('relatorios/relatorio.pdf'));
                         readfile('relatorios/relatorio.pdf');
                         exit;
       }
 
       header('Location: relatorios/relatorio.pdf');
    }
  }
  
   rodape(); 
?>
 