document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

$(document).ready(function () {
    var cleave = new Cleave('#num_rg', {
        blocks: [2, 3, 3, 1],
        delimiters: ['.', '.', '-'],
        uppercase: true,
        numericOnly: false,
        delimiterLazyShow: true
    });
    $('#num_cpf').inputmask("999.999.999-99", { "placeholder": "000.000.000-00" });
    $('#num_tel_cel').inputmask("(99)99999-9999", { "placeholder": "(00)00000-0000" });
    $('#num_tel_fixo').inputmask("(99)9999-9999", { "placeholder": "(00)0000-0000" });
    $('#cep').inputmask("99999-999", { "placeholder": "00000-000" });
    $('#datanasc').inputmask("99/99/9999", { "placeholder": "00/00/0000" });
});

// consulta cep
document.addEventListener('DOMContentLoaded', function () {
    function preencherCampos(dados) {
        document.getElementById('logradouro').value = dados.logradouro;
        document.getElementById('bairro').value = dados.bairro;
    }

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
                     
                } else {
                    console.log('mensagem: ' + response.mensagem); 
                    console.log(response.beneficiario["\u0000*\u0000dados"]);
                    populaCampos(response.beneficiario["\u0000*\u0000dados"]); 

                }
            },
            error: function(error, xhr){
                console.log(error, xhr);
            }
        })
    })

    function consultarCEP(cep) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
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

    var form = document.querySelector('.form-cadastro');
    var fileInput = document.getElementById('fileInput');

    form.addEventListener('submit', function (event) {
        // Verifica se um arquivo foi selecionado
        if (fileInput.files.length > 0) {
            var arquivo = fileInput.files[0];
            // Verifica o tamanho do arquivo (2MB)
            if (arquivo.size > 2 * 1024 * 1024) {
                // Se o arquivo for muito grande, impede o envio do formulário
                Swal.fire({
                    icon: "error",
                    title: "Erro ao fazer o upload do atestado! Selecione um arquivo com menos de 2MB" ,
                    didClose: () => {
                        
                    } 
                })
                event.preventDefault();
            }
        }
    });

    validarCPF();

    // var campoData = document.getElementById('data_cadastro');
    // var campoHora = document.getElementById('hora_cadastro');

    // var dataAtual = new Date();
    // var dataFormatada = dataAtual.toISOString().split('T')[0];
    // campoData.value = dataFormatada;

    // var horaAtual = dataAtual.getHours().toString().padStart(2, '0') + ':' + dataAtual.getMinutes().toString().padStart(2, '0');
    // campoHora.value = horaAtual;

    // $.ajax({
    //     url: 'https://id.who.int/icd/release/11/2022-02/mms',
    //     method: 'GET',
    //     headers: {
    //         'Accept': 'application/json',
    //         'API-Key': '72c98c10-0d07-4f76-aad1-0e437e9e945f_e77ead8d-601c-485d-878c-ea44a1171c57'  // Substitua pela sua chave de API
    //     },
    //     success: function(response) {
    //         // Exibe os resultados no elemento #result
    //         // let output = "<h2>Lista de Códigos CID</h2><ul>";
    //         let output = '';
    //         // Processa cada capítulo e subcategoria
    //         response.forEach(function(item) {
    //             output += "<option value='" + item.code  + "'>" + item.title + "</option>";
    //         });
    //         // output += "</ul>";

    //         $('#select_cid').html(output);
    //     },
    //     error: function(error) {
    //         $('#select_cid').html('<option>Ocorreu um erro ao buscar os códigos CID.</option>');
    //         console.error('Erro:', error);
    //     }
    // });
});


// $('#buscarCID').click(function() {
    // $.ajax({
    //     url: '../api_request.php',
    //     method: 'GET',
    //     // headers: {
    //     //     'Accept': 'application/json',
    //     //     'API-Key': 'SUA_API_KEY'  // Substitua pela sua chave de API
    //     // },
    //     success: function(response) {
    //         // Exibe os resultados no elemento #result
    //         let output = "<h2>Lista de Códigos CID</h2><ul>";
            
    //         // Processa cada capítulo e subcategoria
    //         response.forEach(function(item) {
    //             output += "<li><strong>" + item.code + "</strong>: " + item.title + "</li>";
    //         });
    //         output += "</ul>";

    //         $('#result').html(output);
    //     },
    //     error: function(error) {
    //         $('#result').html('<p>Ocorreu um erro ao buscar os códigos CID.</p>');
    //         console.error('Erro:', error);
    //     }
    // });
// });


// // popup medicamento
// const selectMedicacao = document.getElementById("selectMedicacao");
// const openPopupButtonMedicacao = document.getElementById("openPopupButtonMedicacao");
// const medicacaoPopup = document.getElementById("medicacaoPopup");
// const selectMedicacaoButton = document.getElementById("selectMedicacaoButton");
// const selectedMedicacaoArea = document.getElementById("selectedMedicacao");

// selectMedicacao.addEventListener("change", () => {
//     ativaBotaoPopup(selectMedicacao, openPopupButtonMedicacao);
// });

// openPopupButtonMedicacao.addEventListener("click", () => {
//     // Fazer a requisição AJAX para obter as medicações
//     const xhr = new XMLHttpRequest();
//     xhr.open("GET", "../../selects/obter_medicamentos.php", true);

//     xhr.onload = function () {
//         if (xhr.status === 200) {
//             // Atualizar apenas o conteúdo da div dentro da popup
//             document.querySelector("#medicacaoPopup div").innerHTML = xhr.responseText;
//             // Exibir a popup
//             medicacaoPopup.style.display = "block";
//         } else {
//             console.error("Erro ao obter as medicações.");
//         }
//     };

//     xhr.send();
// });

// selectMedicacaoButton.addEventListener("click", () => {
//     const medicacaoOptions = document.querySelectorAll("#medicacaoOptions input[type=checkbox]:checked");
//     const selectedMedicacao = Array.from(medicacaoOptions).map(option => option.value);

//     selectedMedicacaoArea.innerHTML = "<p>Medicações selecionadas: " + selectedMedicacao.join(", ") + "</p>";

//     medicacaoPopup.style.display = "none";
// });

// document.addEventListener("click", (event) => {
//     if (event.target !== openPopupButtonMedicacao && event.target !== medicacaoPopup && !medicacaoPopup.contains(event.target)) {
//         medicacaoPopup.style.display = "none";
//     }
// });

// //recebe o elemento o elemento selecionado e o elemento botao para liberá-lo
// function ativaBotaoPopup(select, buttonPopup) {
//     if (select.value === "sim") {
//         buttonPopup.disabled = false;
//     } else {
//         buttonPopup.disabled = true;
//     }
// };

// //Ativa botoes ao iniciar página
// document.addEventListener('DOMContentLoaded', function () {
//     const selectDoenca = document.getElementById("selectDoenca");
//     const openPopupButton = document.getElementById("openPopupButton");

//     const selectAlergia = document.getElementById("selectAlergia");
//     const openPopupButtonAlergias = document.getElementById("openPopupButtonAlergias");

//     const selectDefic = document.getElementById("selectDefic");
//     const openPopupButtonDefic = document.getElementById("openPopupButtonDefic");

//     const selectConvenio = document.getElementById("selectConvenio");
//     const openPopupButtonConvenio = document.getElementById("openPopupButtonConvenio");

//     const selectMedicacao = document.getElementById("selectMedicacao");
//     const openPopupButtonMedicacao = document.getElementById("openPopupButtonMedicacao");

//     ativaBotaoPopup(selectDoenca, openPopupButton);
//     ativaBotaoPopup(selectAlergia, openPopupButtonAlergias);
//     ativaBotaoPopup(selectDefic, openPopupButtonDefic);
//     ativaBotaoPopup(selectConvenio, openPopupButtonConvenio);
//     ativaBotaoPopup(selectMedicacao, openPopupButtonMedicacao);
// });

function converterTexto(input) {
    let texto = input.value;

    texto = texto.toUpperCase();

    input.value = texto;
}


// // Aguarde até que o documento esteja pronto
// document.addEventListener('DOMContentLoaded', function () {
//     // Obtenha uma referência ao switch
//     var switchAtualizacoes = document.getElementById('switchAtualizacoes');

//     // Obtenha uma lista de todos os campos atualizáveis
//     var camposAtualizaveis = document.querySelectorAll('.campoAtualizavel');

//     // Adicione um ouvinte de eventos para o switch
//     switchAtualizacoes.addEventListener('change', function () {
//         // Se o switch estiver marcado, habilite os campos, caso contrário, desabilite-os
//         for (var i = 0; i < camposAtualizaveis.length; i++) {
//             camposAtualizaveis[i].disabled = !switchAtualizacoes.checked;
//             // Adicione um contorno cinza quando o campo estiver desativado
//             camposAtualizaveis[i].style.outlineColor = camposAtualizaveis[i].disabled ? 'gray' : '';
//         }
//     });

//     // Chame o evento de mudança para configurar o estado inicial
//     switchAtualizacoes.dispatchEvent(new Event('change'));
// });

        document.addEventListener('DOMContentLoaded', function () {
            
        });

        document.addEventListener('DOMContentLoaded', function () {
            
        });


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
    document.getElementById('rg_orgao').value = beneficiario.RGEXPEDIDOR ?? '';
    document.getElementById('rg_data').value = beneficiario.DTEXPRG ? beneficiario.DTEXPRG.split(' ')[0] : '';
    document.getElementById('cpf').value = beneficiario.CPF ?? '';
    document.getElementById('telefone').value = beneficiario.TELEFONE ?? '';
    document.getElementById('data_nascimento').value = beneficiario.DTNASC ? beneficiario.DTNASC.split(' ')[0] : ''; 
    document.getElementById('cep').value = beneficiario.CEP ?? '';
    document.getElementById('endereco').value = beneficiario.ENDERECO ?? '';
    document.getElementById('bairro').value = beneficiario.BAIRRO ?? '';
    document.getElementById('complemento').value = beneficiario.COMPLEMENTO ?? '';
    document.getElementById('cidade').value = beneficiario.CIDADE ?? '';
    document.getElementById('uf').value = beneficiario.UF ?? '';
    // document.getElementById('comprovante').value = beneficiario.COMPROVANTE ?? ''; // 'atestado' renomeado para 'comprovante'
    document.getElementById('cnh').value = beneficiario.CNH ?? '';
    document.getElementById('validade_cnh').value = beneficiario.CNHVALIDADE ? beneficiario.CNHVALIDADE.split(' ')[0] : '';
    document.getElementById('profissao').value = beneficiario.PROFISSAO ?? '';
    document.getElementById('email').value = beneficiario.EMAIL ?? '';
    if (beneficiario.DEF === 'S') {
        document.getElementById('collapseExample').setAttribute('class', 'collapse show');
        document.getElementById('pne').setAttribute('class', 'form-check-input');
        document.getElementById('pne').setAttribute('aria-expanded', 'true');
        document.getElementById('pne').checked = true;
    }
    document.getElementById('atestado').value = beneficiario.ATESTADO ?? '';
    document.getElementById('crm').value = beneficiario.CRM ?? '';
    document.getElementById('nome_medico').value = beneficiario.MEDICO ?? '';
    document.getElementById('cid').value = beneficiario.CID ?? '';
    document.getElementById('situacao').value = beneficiario.SITUACAOPNE ?? ''; 
    document.getElementById('nome_representante').value = beneficiario.NOMEREP ?? '';
    document.getElementById('email_representante').value = beneficiario.EMAILREP ?? '';
    document.getElementById('rg_representante').value = beneficiario.RGREP ?? '';
    document.getElementById('rg_orgao_representante').value = beneficiario.RGEXPREP ?? '';
    document.getElementById('rg_data_representante').value = beneficiario.DTEXPRGREP ? beneficiario.DTEXPRGREP.split(' ')[0] : '';
    document.getElementById('telefone_representante').value = beneficiario.TELEFONEREP ?? '';
    document.getElementById('cep_representante').value = beneficiario.CEPREP ?? '';
    document.getElementById('endereco_representante').value = beneficiario.ENDERECOREP ?? '';
    document.getElementById('bairro_representante').value = beneficiario.BAIRROREP ?? '';
    document.getElementById('cidade_representante').value = beneficiario.CIDADEREP ?? '';
    document.getElementById('complemento_representante').value = beneficiario.COMPLEMENTOREP ?? '';
    document.getElementById('uf_representante').value = beneficiario.UFREP ?? '';

}
