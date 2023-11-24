<?php

// Geracao de pdf
// Marcelo C Peres 2023
/* Exemplo: 
     if ( CriaPDF ( 'Relatorio de Vendas', 
         '<html> ... </html>', 
         'relatorios/20231031.pdf' ) )  
     {
      echo 'gerado com sucesso';
     }
  */

  function CriaPDF ( $paramTitulo, $paramHtml, $paramArquivoPDF )
  {
   $arq = false;     
   try {  
    require "fpdf/html_table.php"; 
    // abre classe fpdf estendida com recurso que converte <table> em pdf
  
    $pdf = new PDF();  
    // cria um novo objeto $pdf da classe 'pdf' que estende 'fpdf' em 'html_table.php'
    $pdf->AddPage();  // cria uma pagina vazia
    $pdf->SetFont('helvetica','B',20);       
    $pdf->Write(5,$paramTitulo);    
    $pdf->SetFont('helvetica','',8);     
    $pdf->WriteHTML($paramHtml); // renderiza $html na pagina vazia
    ob_end_clean();    
    // fpdf requer tela vazia, essa instrucao 
    // libera a tela antes do output
    
    // gerando um arquivo 
    $pdf->Output('F',$paramArquivoPDF);
    // gerando um download 
    $pdf->Output('D',$paramArquivoPDF,true);  // disponibiliza o pdf gerado pra download
    $arq = true;
   } catch (Exception $e) {
     echo $e->getMessage(); // erros da aplicação - gerais
   }
   return $arq;
  }


// Envio de emails
// Marcelo C Peres 2023
/* Exemplo: 
     if ( EnviaEmail ('fulano@fulano','Feliz Aniversario',
                      '<html><body>Feliz niver</body></html>') 
     {
      echo 'enviado com sucesso';
     }
  */

////////////////////////////////////////////////////////////////
/*
  * Fun��o para ExecutaSQL frases sql
  * marcelo c peres - 2023
  */

function ExecutaSQL($paramConn, $paramSQL)
{
  // exec eh usado para update, delete, insert
  // eh um metodo da conexao
  // retorna o nro de linhas afetadas
  $linhas = $paramConn->exec($paramSQL);

  if ($linhas > 0) {
    return TRUE;
  } else {
    return FALSE;
  }
}

/*
  * Fun��o para executasql frases sql
  * marcelo c peres - 2023
  */

// ValorSQL 
// retorna o valor de um campo de um select
// Set 2023 - Marcelo C Peres 
function ValorSQL($pConn, $pSQL)
{
  $linhas = $pConn->query($pSQL)->fetch();

  if ($linhas > 0) {
    return $linhas[0];
  } else {
    return "0";
  }
}


/**
 * Fun��o para gerar senhas aleat�rias
 *
 * @author    Thiago Belem <contato@thiagobelem.net>
 *
 * @param integer $tamanho Tamanho da senha a ser gerada
 * @param boolean $maiusculas Se ter� letras mai�sculas
 * @param boolean $numeros Se ter� n�meros
 * @param boolean $simbolos Se ter� s�mbolos
 *
 * @return string A senha gerada
 */

function GeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
  //$lmin = 'abcdefghijklmnopqrstuvwxyz';
  $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $num = '1234567890';
  $simb = '!@#$%*-';
  $retorno = '';
  $caracteres = '';

  //$caracteres .= $lmin;
  if ($maiusculas) $caracteres .= $lmai;
  if ($numeros)    $caracteres .= $num;
  if ($simbolos)   $caracteres .= $simb;

  $len = strlen($caracteres);

  for ($n = 1; $n <= $tamanho; $n++) {
    $rand = mt_rand(1, $len);
    $retorno .= $caracteres[$rand - 1];
  }

  return $retorno;
}

//////  funcao de conexao
//////  14-8-2023
function conecta($params = "")
{

  if ($params == "") {
    $params = "pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti18; user=projetoscti18; password=720421";
  }

  $varConn = new PDO($params);

  if (!$varConn) {
    echo "Nao foi possivel conectar";
  } else {
    return $varConn;
  }
}
/////////////////////////

//////  funcao de login
//////  11-9-2023
function funcaoLogin($paramLogin, $paramSenha, &$paramAdmin)
{
  $conn = conecta();
  $varSQL = " select senha,admin from tbl_usuario 
               where email = '$paramLogin' ";
  $linha =  $conn->query($varSQL)->fetch();
  $paramAdmin = $linha['admin'] == true;
  return $linha['senha'] == $paramSenha;
}

//////  funcao de definir cookie
//////  11-9-2023
function DefineCookie($paramNome, $paramValor, $paramMinutos)
{
  echo "Cookie: $paramNome Valor: $paramValor";
  setcookie($paramNome, $paramValor, time() + $paramMinutos * 60);
}

function rodape()
{
  echo "<footer>
    <hr>
    <center>
        &nbsp; <a href='Home.php'>&nbsp; Home &nbsp;</a>&nbsp;
        &nbsp; <a href='Produtos.php'>&nbsp; Produtos &nbsp;</a>&nbsp;
        &nbsp; <a href='Sobre.php'>&nbsp; Sobre &nbsp;</a>&nbsp;
        &nbsp; <a href='Devs.php'>&nbsp; Dev &nbsp;</a>&nbsp;
        <br><br><br>
        <texto>Gabriel - 11</texto> &nbsp;<texto>Helena - 12</texto> &nbsp;<texto>Isabela - 13</texto>
         &nbsp;<texto>Isabelle - 14</texto> &nbsp;<texto>Ivander - 15</texto> &nbsp;
    </center>
</footer>
";
}
