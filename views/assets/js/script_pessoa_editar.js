document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

// $(document).ready(function () {
//     var cleave = new Cleave('#num_rg', {
//         blocks: [2, 3, 3, 1],
//         delimiters: ['.', '.', '-'],
//         uppercase: true,
//         numericOnly: false,
//         delimiterLazyShow: true
//     });
//     $('#num_cpf').inputmask("999.999.999-99", { "placeholder": "000.000.000-00" });
//     $('#num_tel_cel').inputmask("(99)99999-9999", { "placeholder": "(00)00000-0000" });
//     $('#num_tel_fixo').inputmask("(99)9999-9999", { "placeholder": "(00)0000-0000" });
//     $('#cep').inputmask("99999-999", { "placeholder": "00000-000" });
//     $('#datanasc').inputmask("99/99/9999", { "placeholder": "00/00/0000" });
// });

// Variável de controle do caminho do submit
var urlSubmit = `cadastrar`;

// consulta cep
document.addEventListener('DOMContentLoaded', function () {    

    document.getElementById('consultaDOC').addEventListener('click', function(){
        
        const urlBase = this.dataset.url;
        const numDoc = document.getElementById('doc_consulta').value;

        console.log(urlBase);
        $.ajax({
            type: 'post',
            url: urlBase,
            data: {
                doc: numDoc
            },
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
            dataType: 'json', // Especifica o formato esperado da resposta
            success: function(response){
                if(response.status === 0){
                    console.log('mensagem: ' + response.mensagem);
                    // ADICIONAR ALERTA
                    var alerta = new Alerta();
                    alerta.erro(response.mensagem).renderizar();
                } else {
                    console.log('mensagem: ' + response.mensagem); 
                    populaCampos(response.beneficiario["\u0000*\u0000dados"]); 
                }
            },
            error: function(error, xhr){
                console.log(error, xhr);
            }
        })
    })

    function preencherCampos(dados) {
        document.getElementById('endereco').value = dados.logradouro;
        document.getElementById('bairro').value = dados.bairro;
        document.getElementById('cidade').value = dados.localidade;
        document.getElementById('uf').value = dados.uf;
    }
    
    function consultarCEP(cep) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    console.log(data);
                    preencherCampos(data);
                } else {
                    alert('CEP não encontrado. Verifique o CEP digitado.');
                }
            })
            .catch(error => console.error('Erro na consulta: ', error));
    }

    document.getElementById('cep').addEventListener('focusout', function () {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length === 8) {
            consultarCEP(cep);
        } else {
            alert('CEP inválido. Digite um CEP válido.');
        }
    });

    document.getElementById('consultaCEP').addEventListener('click', function () {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length === 8) {
            consultarCEP(cep);
        } else {
            alert('CEP inválido. Digite um CEP válido.');
        }
    });

    validarCPF();

    document.getElementById('form-cadastro').addEventListener('submit', function(event){
        event.preventDefault();

        // Seleciona o formulário
        const form = event.target;

        // Cria um objeto FormData a partir do formulário
        const formData = new FormData(form);

        // Converte os dados do FormData para um formato de URL (formato de formulário)
        const formEncoded = new URLSearchParams(formData).toString();

        $.ajax({
            type: 'post', 
            url: 'editar',
            data: formEncoded,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
            dataType: 'json', // Especifica o formato esperado da resposta
            success: function(response){
                 // Depuração para verificar a resposta recebida
                console.log(response);
                if(response.status === 0){
                    // console.log('mensagem: ' + response);
                    var alerta = new Alerta();
                    alerta.erro(response.mensagem).renderizar();
                } else {
                    // console.log('mensagem: ' + response); 
                    var alerta = new Alerta();
                    alerta.sucesso(response.mensagem, {urlConfirmar: response.urlConfirmar}).renderizar();
                }
            },
            error: function(error, xhr){
                console.log(error, xhr);
            }
        })
    })

    document.getElementById('def').addEventListener('input', function (){
        if(document.getElementById('def').checked === true){
            document.getElementById('medico').required = true;
            document.getElementById('cid').required = true;
            document.getElementById('nome_representante').required = true;
            document.getElementById('rg_representante').required = true;
            document.getElementById('telefone_representante').required = true;
            document.getElementById('endereco_representante').required = true;
            document.getElementById('bairro_representante').required = true;
            document.getElementById('cidade_representante').required = true;
        } else {
            document.getElementById('medico').required = false;
            document.getElementById('cid').required = false;
            document.getElementById('nome_representante').required = false;
            document.getElementById('rg_representante').required = false;
            document.getElementById('telefone_representante').required = false;
            document.getElementById('endereco_representante').required = false;
            document.getElementById('bairro_representante').required = false;
            document.getElementById('cidade_representante').required = false;
        }
    })

});

function converterTexto(input) {
    let texto = input.value;

    texto = texto.toUpperCase();

    input.value = texto;
}


function validarCPF() {
    var campoCpf = document.getElementById('cpf');
    var labelCpf = document.querySelector('label[for="cpf"]');
    var botaoCadastro = document.getElementById('botaoCadastro'); // Seleciona o botão de cadastro

    campoCpf.addEventListener('input', (event) => {
        // Verifica se o CPF é válido
        var isValid = validaCPF(event.target.value);
    
        // Atualiza o elemento com a mensagem de validação
        if (isValid) {
            labelCpf.innerHTML = "CPF";
            botaoCadastro.disabled = false; // Habilita o botão de cadastro
        } else {
            labelCpf.innerHTML = 'CPF - <span style="color: red;">Inválido</span>';
            botaoCadastro.disabled = true; // Desabilita o botão de cadastro
        }
    })
}

function validaCPF(cpf) {
    // Extrai somente os números
    cpf = cpf.replace(/\D/g, '');

    // Verifica se foi informado todos os dígitos corretamente
    if (cpf.length !== 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de dígitos repetidos. Ex: 111.111.111-11
    if (/^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    // Faz o cálculo para validar o CPF
    let soma = 0;
    let resto;

    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }

    resto = (soma * 10) % 11;

    if ((resto === 10) || (resto === 11)) {
        resto = 0;
    }

    if (resto !== parseInt(cpf.substring(9, 10))) {
        return false;
    }

    soma = 0;

    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }

    resto = (soma * 10) % 11;

    if ((resto === 10) || (resto === 11)) {
        resto = 0;
    }

    if (resto !== parseInt(cpf.substring(10, 11))) {
        return false;
    }

    return true;
}

function populaCampos(beneficiario){
    console.log(beneficiario.NOME);
    document.getElementById('nomecompleto').value = beneficiario.NOME ?? '';
    document.getElementsByName('sexo').forEach((element) => element.checked = element.value === beneficiario.SEXO);
    document.getElementById('rg').value = beneficiario.RG ?? '';
    document.getElementById('rg_expedidor').value = beneficiario.RG_EXPEDIDOR ?? '';
    document.getElementById('rg_data').value = beneficiario.RG_DATA ? beneficiario.RG_DATA.split(' ')[0] : '';
    document.getElementById('cpf').value = beneficiario.CPF ?? '';
    document.getElementById('telefone').value = beneficiario.TELEFONE ?? '';
    document.getElementById('data_nascimento').value = beneficiario.DATA_NASCIMENTO ? beneficiario.DATA_NASCIMENTO.split(' ')[0] : ''; 
    document.getElementById('cep').value = beneficiario.CEP ?? '';
    document.getElementById('endereco').value = beneficiario.ENDERECO ?? '';
    document.getElementById('bairro').value = beneficiario.BAIRRO ?? '';
    document.getElementById('complemento').value = beneficiario.COMPLEMENTO ?? '';
    document.getElementById('cidade').value = beneficiario.CIDADE ?? '';
    document.getElementById('uf').value = beneficiario.UF ?? '';
    // document.getElementById('comprovante').value = beneficiario.COMPROVANTE ?? ''; // 'atestado' renomeado para 'comprovante'
    document.getElementById('cnh').value = beneficiario.CNH ?? '';
    document.getElementById('cnh_validade').value = beneficiario.CNH_VALIDADE ? beneficiario.CNH_VALIDADE.split(' ')[0] : '';
    document.getElementById('profissao').value = beneficiario.PROFISSAO ?? '';
    document.getElementById('email').value = beneficiario.EMAIL ?? '';
    if (beneficiario.DEF === 'S') {
        document.getElementById('collapseExample').setAttribute('class', 'collapse show');
        document.getElementById('def').setAttribute('class', 'form-check-input');
        document.getElementById('def').setAttribute('aria-expanded', 'true');
        document.getElementById('def').checked = true;
    } else {
        document.getElementById('collapseExample').setAttribute('class', 'collapse');
        document.getElementById('def').setAttribute('class', 'form-check-input');
        document.getElementById('def').setAttribute('aria-expanded', 'false');
        document.getElementById('def').checked = false;
    }
    document.getElementById('atestado').value = beneficiario.ATESTADO ?? '';
    // document.getElementById('crm').value = beneficiario.CRM ?? '';
    document.getElementById('medico').value = beneficiario.MEDICO ?? '';
    document.getElementById('cid').value = beneficiario.CID ?? '';
    document.getElementById('situacao_pne').value = beneficiario.SITUACAO_PNE ?? ''; 
    document.getElementById('nome_representante').value = beneficiario.NOMEREP ?? '';
    document.getElementById('email_representante').value = beneficiario.EMAILREP ?? '';
    document.getElementById('cpf_representante').value = beneficiario.CPFREP ?? '';
    document.getElementById('telefone_representante').value = beneficiario.TELEFONEREP ?? '';
    document.getElementById('cep_representante').value = beneficiario.CEPREP ?? '';
    document.getElementById('endereco_representante').value = beneficiario.ENDERECOREP ?? '';
    document.getElementById('bairro_representante').value = beneficiario.BAIRROREP ?? '';
    document.getElementById('cidade_representante').value = beneficiario.CIDADEREP ?? '';
    document.getElementById('complemento_representante').value = beneficiario.COMPLEMENTOREP ?? '';
    document.getElementById('uf_representante').value = beneficiario.UFREP ?? '';

}
