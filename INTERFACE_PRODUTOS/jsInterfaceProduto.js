function slider (anything){
    document.querySelector ('.principal') .src = anything;
};

let menu = document.querySelector ('#menu-icon');
let navbar = document.querySelector ('.navbar');

menu.onclick = () => {
    menu.classList.toggle ('bx-x');
    navbar.classList.toggle ('open');
}

function btnComprar()
{

    alert ('Compra realizada com sucesso!')
    
}
function btnCompraInvalida()
{
    alert('Impossivel realizar a compra, estoque insuficiente!')
    
}

function btnCarrinho()
{
    alert ('Item adicionado ao carrinho.')
}

function btnExcluir()
{
    alert ('Item excluído com sucesso')
}