const InputPessoa = document.querySelector('#inputPessoa');

function validarCPF(cpf) {
    cpf = cpf.replace(/[.-]/g, '');
    if ( !cpf || cpf.length != 11
        || cpf == "00000000000"
        || cpf == "11111111111"
        || cpf == "22222222222" 
        || cpf == "33333333333" 
        || cpf == "44444444444" 
        || cpf == "55555555555" 
        || cpf == "66666666666"
        || cpf == "77777777777"
        || cpf == "88888888888" 
        || cpf == "99999999999" )
    return false
    var soma = 0
    var resto
    
    for (var i = 1; i <= 9; i++) 
        soma = soma + parseInt(cpf.substring(i-1, i)) * (11 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(9, 10)) ) return false
    soma = 0
    for (var i = 1; i <= 10; i++) 
        soma = soma + parseInt(cpf.substring(i-1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11))  resto = 0
    if (resto != parseInt(cpf.substring(10, 11) ) ) return false
    return true
}

InputPessoa.addEventListener('change', function () {
    
    $(InputPessoa).inputmask('remove');
    InputPessoa.maxLength = 14;
    $(InputPessoa).inputmask('999.999.999-99');
    
    
    }
);

InputPessoa.addEventListener('blur', function () {
    const inputPessoaValue = InputPessoa.value;

    const cpfValido = validarCPF(inputPessoaValue);
    if (!cpfValido) {
        alert('CPF inválido');
        InputPessoa.value = '';
    }
    
});





const formsPessoa = document.querySelector(".formCadastro");

const inputCEP = document.querySelector("#CEP");
const inputLogradouro = document.querySelector("#logradouro");
const inputCidade = document.querySelector("#cidade");
const inputRegiao = document.querySelector("#regiao");

inputCEP.addEventListener("keypress", (e) =>{
    const ApenasNumeros = /[0-9]|\./;
    const digitos = e.key;

    // allow only numbers
    if (!ApenasNumeros.test(digitos)) {
    e.preventDefault();
    return;
    }
});

inputCEP.addEventListener("keyup" , (e)=> {
    const CEP = e.target.value;

    if(CEP.length === 5){
        inputCEP.value = CEP.replace(/(\d{5})/,'$1-');
    }
    if(CEP.length === 9){
        pegaCEP(CEP);
    }
});

function removeClickSelect() {

}

const pegaCEP = async (cep) => {
    inputCEP.blur();

    const apiUrl = `https://viacep.com.br/ws/${cep}/json/`;

    const resposta = await fetch(apiUrl);

    const data = await resposta.json();
    
    if(data.erro !== "true"){
        inputLogradouro.value = data.logradouro;
        inputCidade.value = data.localidade;
        inputRegiao.value = data.uf;

        inputLogradouro.readOnly = true;
        inputCidade.readOnly = true;
        inputRegiao.readOnly = true;

        inputLogradouro.disabled = false;
        inputCidade.disabled = false;
        inputRegiao.disabled = false;
        
    }else{
        inputCEP.value = "";
        alert("Erro na hora de digitar o cep, tente novamente");
        return;
    }

   
}