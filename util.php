<?php 
 
  //////  funcao de conexao
  //////  14-8-2023
  function conecta ($params = "")  // igual a nada pra indicar q aceita vazio !! 
  {
    if ($params == "") {
        $params="pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti18; user=projetoscti18; password=720421";
    }

    $varConn = new PDO($params);

    if (!$varConn) {
        echo "Nao foi possivel conectar";
    } else { return $varConn; }
  }
  /////////////////////////

  //////  funcao de login
  //////  11-9-2023
  function funcaoLogin ($paramLogin, $paramSenha, &$paramAdmin)  
  {
   $conn = conecta();  
   $varSQL = " select senha_usuario,admin_usuario from tbl_usuario
               where email_usuario = '$paramLogin' "; 

   echo $varSQL;
   
   $linha =  $conn->query($varSQL)->fetch();
   var_dump($linha);
   $paramAdmin = $linha['admin_usuario'] == 's';
   return $linha['senha_usuario'] == $paramSenha;  
  }

  //////  funcao de definir cookie
  //////  11-9-2023
  function DefineCookie($paramNome, $paramValor, $paramMinutos) 
  {
   echo "Cookie: $paramNome Valor: $paramValor";  
   setcookie($paramNome, $paramValor, time() + $paramMinutos * 60); 
  }
?>