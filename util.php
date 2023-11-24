<?php 

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
    $pdf->Output($paramArquivoPDF,'D');  // disponibiliza o pdf gerado pra download
    $arq = true;
   } catch (Exception $e) {
     echo $e->getMessage(); // erros da aplicação - gerais
   }
   return $arq;
  }


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'dependencia/vendor/autoload.php';

function EnviaEmail($emailEnviado, $nomeEnviado, $senhaEnviada){
    $mail = new PHPMailer(true);
    
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                      //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'joao.jacob@unesp.br';                     //SMTP username
            $mail->Password   = 'psyupfvhykhuehlz';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
            //Recipients
            $mail->setFrom('joao.jacob@unesp.br', 'Administrção');
            $mail->addAddress($emailEnviado, $nomeEnviado);     //Add a recipient
            $mail->addReplyTo('joao.jacob@unesp.br', 'Information');
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Senha de recuperação';
            $body = "
            <body>
                Olá, ".$nomeEnviado.", o email que você
                desejou que enviassemos o email é:".$emailEnviado.
                "<br> A senha de recuperação é <br>
                <strong>".$senhaEnviada."</strong>
            </body>";

            $mail->Body    = $body ;
            $mail->send();
            echo 'Mensagem enviada';
        } catch (Exception $e) {
            echo "Deu merda";
        }
    }


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
      $retorno .= $caracteres[$rand-1];
  }
  
  return $retorno;
}




  function conecta ($params="") 
  {
    
    if ($params == "") {
      $params="pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti18; user=projetoscti18; password=720421";
  }
    
    $varConn = new PDO($params);

    if (!$varConn) {
        echo "Nao foi possivel conectar";
    } else { return $varConn; }
  }

  function funcaoLogin ($paramLogin, $paramSenha, &$paramAdmin)  
  {
   $conn = conecta();  
   $varSQL = " select senha_usuario,admin_usuario from tbl_usuario
               where email_usuario = '$paramLogin' and senha_usuario = '$paramSenha' ";
   $linha =  $conn->query($varSQL)->fetch();
   var_dump($linha);
   if($linha != false){
        $paramAdmin = $linha['admin_usuario'] == 's';
        return $linha['senha_usuario'] == $paramSenha;  
   }else{
        return false;
   }
  }

  function DefineCookie($paramNome, $paramValor, $paramMinutos) 
  {
   echo "Cookie: $paramNome Valor: $paramValor";  
   setcookie($paramNome, $paramValor, time() + $paramMinutos * 60); 
  }

  function ExecutaSQL( $paramConn, $paramSQL ) 
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
  // ValorSQL 
  // retorna o valor de um campo de um select
  // Set 2023 - Marcelo C Peres 
  function ValorSQL( $pConn, $pSQL ) 
  {
   $linhas = $pConn->query($pSQL)->fetch();  
   if ($linhas > 0) { 
       return $linhas[0]; 
   } else { 
       return "0"; 
   }  
  }
?>