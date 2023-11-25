<!DOCTYPE html>
<html lang='pt-br'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Relatório</title>
    <link rel='stylesheet' type='text/css' href='relatorio.css'>
</head>
<body>
    <div class='page'>
        <form action='relatorio.php' method='POST' class='formRelatorio'>

            <a href='../frmLoginUsuario.php' id='arrow-btn'>
                <img src='../imagens/seta.svg' alt='Seta'>
            </a>

            <h1>RELATÓRIO</h1>

            <label for='dInicial'>Data inicial:</label>
            <input class = "campo" type='date' name='dInicial' id='dInicial' value= <?php $ontem ?> ><br>

            <label for='dFinal'>Data final:</label> 
            <input class = "campo" type='date' name='dFinal' id='dFinal' value=' <?php (isset($_POST['dataf']) ? $_POST['dataf'] : $ontem) ?> '><br>


            <label>Selecione o tipo de pdf que deseja visualizar:</label><br>

            <label>Gerar PDF</label>
            <input class = "rodape" type='radio' name='acao' value='GeraPdf'>
            <label>Baixar</label>
            <input class = "rodape" type='radio' name='acao' value='baixar'>

            <button type='submit' class='btn'>Enviar</button>
        </form>
    </div>
</body>
</html>