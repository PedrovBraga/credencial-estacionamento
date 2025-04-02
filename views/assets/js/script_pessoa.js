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

    // validarDocumento();

    function atualizaValidade(){
        const campoDtEmissao = document.getElementById('dtemissao_credencial');
        const campoDtValidade = document.getElementById('validade_credencial');
        const checkboxDeficiente = document.getElementById('def');
        const selectTempoDeficiente = document.getElementById('situacao_pne');
        const selectTipoCredencial = document.getElementById('tipo_credencial');
        let anosParaAdicionar = 5;
        
        if (checkboxDeficiente.value === 'S') {
            const dataEmissao = new Date(campoDtEmissao.value); // Converte o valor para uma data
    
            if (selectTempoDeficiente.value === 'TEMPORARIO') {
                anosParaAdicionar = 1; // Adiciona 1 ano
            }
    
            // Adiciona os anos à data de emissão
            dataEmissao.setFullYear(dataEmissao.getFullYear() + anosParaAdicionar);
    
            // Converte a data para o formato YYYY-MM-DD
            const dataFormatada = dataEmissao.toISOString().split('T')[0];
    
            // Define o valor no campo de validade
            campoDtValidade.value = dataFormatada;
        } else {
            campoDtValidade.value = anosParaAdicionar;
        }
    }

    function desabilitaTipoCredencial(){
        const tipoCredencial = document.getElementById('tipo_credencial');
        const checkboxDeficiente = document.getElementById('def');
        
        const optionDeficiente = Array.from(tipoCredencial.options).find(option => option.value === 'DEFICIENTE');
        if (checkboxDeficiente.value !== 'S') {
            // Desabilitar a opção com valor 'DEFICIENTE'
            if (optionDeficiente) {
                optionDeficiente.disabled = true;
            }
        } else {
            // Reabilitar a opção com valor 'DEFICIENTE'
            if (optionDeficiente) {
                optionDeficiente.disabled = false;
            }
        }
    }

    desabilitaTipoCredencial()

    document.getElementById('situacao_pne').addEventListener('input', function (){
        atualizaValidade();
    })
    
    document.getElementById('tipo_credencial').addEventListener('input', function (){
        atualizaValidade();
        desabilitaTipoCredencial()
    })
    

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
                    var alerta = new Alerta();
                    alerta.erro(response.mensagem).renderizar();
                } else {
                    console.log('mensagem: ' + response.mensagem); 
                    console.log(response.beneficiario["\u0000*\u0000dados"]);
                    populaCampos(response.beneficiario["\u0000*\u0000dados"]); 
                    urlSubmit = `credencial/registrar`;
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
    
    function preencherCamposRepresentante(dados) {
        document.getElementById('endereco_representante').value = dados.logradouro;
        document.getElementById('bairro_representante').value = dados.bairro;
        document.getElementById('cidade_representante').value = dados.localidade;
        document.getElementById('uf_representante').value = dados.uf;
    }
    
    function consultarCEP(cep, tipoPessoa = 'beneficiario') {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    console.log(data);
                    tipoPessoa === 'beneficiario' ? preencherCampos(data) : preencherCamposRepresentante(data);
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
    
    document.getElementById('cep_representante').addEventListener('focusout', function () {
        const cep = document.getElementById('cep_representante').value.replace(/\D/g, '');
        if (cep.length === 8) {
            consultarCEP(cep, 'representante');
        } else {
            alert('CEP inválido. Digite um CEP válido.');
        }
    });

    document.getElementById('consultaCEP_representante').addEventListener('click', function () {
        const cep = document.getElementById('cep_representante').value.replace(/\D/g, '');
        if (cep.length === 8) {
            consultarCEP(cep, 'representante');
        } else {
            alert('CEP inválido. Digite um CEP válido.');
        }
    });

    // validarCPF();

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
            url: urlSubmit,
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
            document.getElementById('cpf_representante').required = true;
            document.getElementById('telefone_representante').required = true;
            document.getElementById('endereco_representante').required = true;
            document.getElementById('bairro_representante').required = true;
            document.getElementById('cidade_representante').required = true;
        } else {
            document.getElementById('medico').required = false;
            document.getElementById('cid').required = false;
            document.getElementById('nome_representante').required = false;
            document.getElementById('cpf_representante').required = false;
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

function validarDocumento(campo) {
    
    if (!campo.value) {
        return;
    }

    const valor = campo.value.replace(/\D/g, ''); // Remove caracteres não numéricos
    const labelDocumento = document.querySelector(`label[for="${campo.id}"]`);

    // Remove "válido", "inválido" e o asterisco do texto original
    let textLabel = labelDocumento.textContent.replace(/(\*| válido| inválido)$/i, '').trim();
    var botaoCadastro = document.getElementById('botaoCadastro');

    if (!valor) {
        // Se o campo estiver vazio, reseta o label
        labelDocumento.textContent = `${textLabel}`;
        labelDocumento.style.color = "#545454";
        return;
    }

    // Verifica se é um CPF ou CNPJ
    if (valor.length <= 11) {
        // Valida CPF
        if (valor.length === 11 && validaCPF(valor)) {
            labelDocumento.textContent = `${textLabel} válido`;
            labelDocumento.style.color = "green";
            botaoCadastro.disabled = false; // Habilita o botão
        } else if (valor.length === 11) {
            labelDocumento.textContent = `${textLabel} inválido`;
            labelDocumento.style.color = "red";
            botaoCadastro.disabled = true; // Desabilita o botão
        } else {
            labelDocumento.textContent = `${textLabel}`;
            labelDocumento.style.color = "#545454";
            botaoCadastro.disabled = true; // Desabilita o botão
        }

        // Formata como CPF
        // campo.value = formatarCPF(valor);
    } else if (valor.length <= 14) {
        // Valida CNPJ
        if (valor.length === 14 && validaCNPJ(valor)) {
            labelDocumento.textContent = `${textLabel} válido`;
            labelDocumento.style.color = "green";
            botaoCadastro.disabled = false; // Habilita o botão
        } else if (valor.length === 14) {
            labelDocumento.textContent = `${textLabel} inválido`;
            labelDocumento.style.color = "red";
            botaoCadastro.disabled = true; // Desabilita o botão
        } else {
            labelDocumento.textContent = `${textLabel}`;
            labelDocumento.style.color = "#545454";
            botaoCadastro.disabled = true; // Desabilita o botão
        }

        // Formata como CNPJ
        // campo.value = formatarCNPJ(valor);
    } else {
        // labelDocumento.textContent = "Documento inválido";
        labelDocumento.style.color = "red";
        botaoCadastro.disabled = true; // Desabilita o botão
    }
}


function validaCPF(cpf) {
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

    let soma = 0, resto;
    for (let i = 1; i <= 9; i++) soma += parseInt(cpf[i - 1]) * (11 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf[9])) return false;

    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf[i - 1]) * (12 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    return resto === parseInt(cpf[10]);
}

function validaCNPJ(cnpj) {
    if (cnpj.length !== 14) return false;

    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0, pos = tamanho - 7;

    for (let i = tamanho; i >= 1; i--) {
        soma += parseInt(numeros.charAt(tamanho - i)) * pos--;
        if (pos < 2) pos = 9;
    }

    let resto = soma % 11;
    resto = resto < 2 ? 0 : 11 - resto;
    if (resto !== parseInt(digitos.charAt(0))) return false;

    tamanho++;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;

    for (let i = tamanho; i >= 1; i--) {
        soma += parseInt(numeros.charAt(tamanho - i)) * pos--;
        if (pos < 2) pos = 9;
    }

    resto = soma % 11;
    resto = resto < 2 ? 0 : 11 - resto;
    return resto === parseInt(digitos.charAt(1));
}

// Não utilizado devido ao BD despadronizado
// function formatarCPF(cpf) {
//     return cpf
//         .replace(/(\d{3})(\d)/, "$1.$2")
//         .replace(/(\d{3})(\d)/, "$1.$2")
//         .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
// }

// function formatarCNPJ(cnpj) {
//     return cnpj
//         .replace(/(\d{2})(\d)/, "$1.$2")
//         .replace(/(\d{3})(\d)/, "$1.$2")
//         .replace(/(\d{3})(\d{1,6})$/, "$1/$2")
//         .replace(/(\d{4})(\d{1,2})$/, "$1-$2");
// }

function formatarCEP(campo) {
    let valor = campo.value.replace(/\D/g, ''); // Remove tudo que não for número

    // Adiciona o hífen no formato XXXXX-XXX
    if (valor.length > 5) {
        valor = valor.replace(/(\d{5})(\d{1,3})$/, "$1-$2");
    }

    campo.value = valor; // Atualiza o valor do campo em tempo real
}

function formatarTelefone(campo) {
    let valor = campo.value.replace(/\D/g, ''); // Remove tudo que não for número

    // Adiciona o hífen no formato XXXXX-XXX
    if (valor.length >= 2) {
        valor = valor.replace(/^(\d{2})(\d)/, "($1)$2");
    }

    if (valor.length > 6) {
        // Fixo com 8 dígitos
        valor = valor.replace(/(\d{4})(\d{4})$/, "$1-$2");
    }
    
     // Formata o número principal (fixo ou celular)
     if (valor.length > 10) {
        // Celular com 9 dígitos
        valor = valor.replace(/(\d{5})(\d{4})$/, "$1-$2");
    }

    campo.value = valor; // Atualiza o valor do campo em tempo real
}

function populaCampos(beneficiario) {
    console.log(beneficiario.NOME);

    document.getElementById('nomecompleto').value = beneficiario.NOME ?? '';
    document.getElementById('nomecompleto').readOnly = true;

    document.getElementsByName('sexo').forEach((element) => {
        element.checked = element.value === beneficiario.SEXO;
        element.disabled = true;
    });

    document.getElementById('rg').value = beneficiario.RG ?? '';
    document.getElementById('rg').readOnly = true;

    document.getElementById('rg_expedidor').value = beneficiario.RG_EXPEDIDOR ?? '';
    document.getElementById('rg_expedidor').readOnly = true;

    document.getElementById('rg_data').value = beneficiario.RG_DATA ? beneficiario.RG_DATA.split(' ')[0] : '';
    document.getElementById('rg_data').readOnly = true;

    document.getElementById('cpfCnpj').value = beneficiario.CPF ?? '';
    document.getElementById('cpfCnpj').readOnly = true;

    document.getElementById('telefone').value = beneficiario.TELEFONE ?? '';
    document.getElementById('telefone').readOnly = true;

    document.getElementById('data_nascimento').value = beneficiario.DATA_NASCIMENTO ? beneficiario.DATA_NASCIMENTO.split(' ')[0] : '';
    document.getElementById('data_nascimento').readOnly = true;

    document.getElementById('cep').value = beneficiario.CEP ?? '';
    document.getElementById('cep').readOnly = true;

    document.getElementById('endereco').value = beneficiario.ENDERECO ?? '';
    document.getElementById('endereco').readOnly = true;

    document.getElementById('bairro').value = beneficiario.BAIRRO ?? '';
    document.getElementById('bairro').readOnly = true;

    document.getElementById('complemento').value = beneficiario.COMPLEMENTO ?? '';
    document.getElementById('complemento').readOnly = true;

    document.getElementById('cidade').value = beneficiario.CIDADE ?? '';
    document.getElementById('cidade').readOnly = true;

    document.getElementById('uf').value = beneficiario.UF ?? '';
    document.getElementById('uf').readOnly = true;

    document.getElementById('cnh').value = beneficiario.CNH ?? '';
    document.getElementById('cnh').readOnly = true;

    document.getElementById('cnh_validade').value = beneficiario.CNH_VALIDADE ? beneficiario.CNH_VALIDADE.split(' ')[0] : '';
    document.getElementById('cnh_validade').readOnly = true;

    document.getElementById('profissao').value = beneficiario.PROFISSAO ?? '';
    document.getElementById('profissao').readOnly = true;

    document.getElementById('email').value = beneficiario.EMAIL ?? '';
    document.getElementById('email').readOnly = true;

    if (beneficiario.DEF === 'S') {
        document.getElementById('collapseExample').setAttribute('class', 'collapse show');
        document.getElementById('def').setAttribute('class', 'form-check-input');
        document.getElementById('def').setAttribute('aria-expanded', 'true');
        document.getElementById('def').checked = true;
        document.getElementById('def').disabled = true;
    }

    document.getElementById('atestado').value = beneficiario.ATESTADO ?? '';
    document.getElementById('atestado').readOnly = true;

    document.getElementById('medico').value = beneficiario.MEDICO ?? '';
    document.getElementById('medico').readOnly = true;

    document.getElementById('cid').value = beneficiario.CID ?? '';
    document.getElementById('cid').readOnly = true;

    document.getElementById('situacao_pne').value = beneficiario.SITUACAO_PNE ?? '';
    document.getElementById('situacao_pne').readOnly = true;

    document.getElementById('nome_representante').value = beneficiario.NOMEREP ?? '';
    document.getElementById('nome_representante').readOnly = true;

    document.getElementById('email_representante').value = beneficiario.EMAILREP ?? '';
    document.getElementById('email_representante').readOnly = true;

    document.getElementById('cpf_representante').value = beneficiario.CPFREP ?? '';
    document.getElementById('cpf_representante').readOnly = true;

    document.getElementById('telefone_representante').value = beneficiario.TELEFONEREP ?? '';
    document.getElementById('telefone_representante').readOnly = true;

    document.getElementById('cep_representante').value = beneficiario.CEPREP ?? '';
    document.getElementById('cep_representante').readOnly = true;

    document.getElementById('endereco_representante').value = beneficiario.ENDERECOREP ?? '';
    document.getElementById('endereco_representante').readOnly = true;

    document.getElementById('bairro_representante').value = beneficiario.BAIRROREP ?? '';
    document.getElementById('bairro_representante').readOnly = true;

    document.getElementById('cidade_representante').value = beneficiario.CIDADEREP ?? '';
    document.getElementById('cidade_representante').readOnly = true;

    document.getElementById('complemento_representante').value = beneficiario.COMPLEMENTOREP ?? '';
    document.getElementById('complemento_representante').readOnly = true;

    document.getElementById('uf_representante').value = beneficiario.UFREP ?? '';
    document.getElementById('uf_representante').readOnly = true;
}


