<?php
  // mostra erros do php
  ini_set ( 'display_errors' , 1); 
  error_reporting (E_ALL);   

  include("util.php");

  $conn = conecta();
  
  session_start();
 
  ////////////////////////////////////////////////////////////////////////////////
  ///////////// APAGAR O CARRINHO DA COMPRA PENDENTE
  ////////////////////////////////////////////////////////////////////////////////

  // captura id da sessao 
  $session_id = session_id();

  // obtem cod_compra de tmpcompras pelo id
  $cod_compra = ValorSQL($conn, " select fk_compra from tbl_compra_temporaria  
                                  where id_sessao = '$session_id' ");                                                                 

  // se o usuario nao logou na compra,                                 
  $excluir_compra_pendente = intval(ValorSQL($conn, " select count(*) from tbl_compra 
                                              where id_compra = $cod_compra 
                                              and status_pedido = 'Pendente' ")) > 0;
  if ( $excluir_compra_pendente ) {
       // ... exclue a compra pendente 
       ExecutaSQL($conn, " delete from tbl_compra_temporaria 
                           where fk_compra = $cod_compra ");
       ExecutaSQL($conn, " delete from tbl_compra_produto 
                           where fk_compra = $cod_compra ");
       ExecutaSQL($conn, " delete from tbl_compra 
                           where id_compra = $cod_compra ");
  }                                                 

  ////////////////////////////////////////////////////////////////////////////////
  ///////////// APAGAR COMPRAS CANCELADAS - NAO CONCLUIDAS
  ////////////////////////////////////////////////////////////////////////////////

  $hoje = date('Y-m-d');
  $AntesDeOntem = date('Y-m-d',(strtotime ( '-2 day' , strtotime ( $hoje ) ) ));
  
  // cancela todas as compras nao concluidas até antes de ontem
  ExecutaSQL($conn, " update tbl_compra set status_pedido = 'Cancelada'  
                      where status_pedido = 'Pendente' and data <= '$AntesDeOntem' ");

  // exclue todos as compras canceladas
  ExecutaSQL($conn, " delete from tbl_compra_produto 
                      where fk_compra in 
                      ( select id_compra from tbl_compra where status_pedido = 'Cancelada' ) ");

  ExecutaSQL($conn, " delete from tbl_compra_temporaria  
                      where fk_compra in 
                      ( select id_compra from tbl_compra where status_pedido = 'Cancelada' ) ");

  ExecutaSQL($conn, " delete from tbl_compra  
                      where status_pedido = 'Cancelada' ");

  ////////////////////////////////////////////////////////////////////////////////
  ///////////// FECHA A SESSAO
  ////////////////////////////////////////////////////////////////////////////////

  // apaga cookie espelho id de sessao                  
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
  );  
  
  // destroi variaveis de sessao
  session_destroy();  
  session_write_close();  

  // var sessao que indica se esta logado
  unset($_SESSION['sessaoConectado']); 
  
  // var sessao que indica que eh adm
  unset($_SESSION['sessaoAdmin']);
  
  // var sessao que possui o login
  unset($_SESSION['sessaoLogin']);

  setcookie('loginCookie', $paramValor, 0);

  header('Location: HOME/homepage.html');
?>