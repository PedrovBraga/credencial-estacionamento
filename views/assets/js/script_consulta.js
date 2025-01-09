document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

$(document).ready(function () {
    $('#num_rg').inputmask("99.999.999[-9]", { "placeholder": "00.000.000-0" });
    $('#num_cpf').inputmask("999.999.999-99", { "placeholder": "000.000.000-00" });
    $('#num_tel_cel').inputmask("(99)99999-9999", { "placeholder": "(00)00000-0000" });
    $('#num_tel_cel_emerg').inputmask("(99)99999-9999", { "placeholder": "(00)00000-0000" });
    $('#num_tel_fixo').inputmask("(99)9999-9999", { "placeholder": "(00)0000-0000" });
    $('#cep').inputmask("99999-999", { "placeholder": "00000-000" });
});

// consulta cep
document.addEventListener('DOMContentLoaded', function() {
    function preencherCampos(dados) {
        document.getElementById('logradouro').value = dados.logradouro;
        document.getElementById('bairro').value = dados.bairro;
    }

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

    document.getElementById('consultaCEP').addEventListener('click', function() {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length === 8) {
            consultarCEP(cep);
        } else {
            alert('CEP inválido. Digite um CEP válido.');
        }
    });
});



// popup doença
const selectDoenca = document.getElementById("selectDoenca");
const openPopupButton = document.getElementById("openPopupButton");
const diseasePopup = document.getElementById("diseasePopup");
const selectDiseasesButton = document.getElementById("selectDiseasesButton");
const selectedDiseasesArea = document.getElementById("selectedDiseases");

selectDoenca.addEventListener("change", () => {
    if (selectDoenca.value === "sim") {
    openPopupButton.disabled = false;
    } else {
    openPopupButton.disabled = true;
    }
});

openPopupButton.addEventListener("click", () => {
    diseasePopup.style.display = "block";
});

selectDiseasesButton.addEventListener("click", () => {
    const diseaseOptions = document.querySelectorAll("#diseaseOptions input[type=checkbox]:checked");
    const selectedDiseases = Array.from(diseaseOptions).map(option => option.value);

    selectedDiseasesArea.innerHTML = "<p>Doenças selecionadas: " + selectedDiseases.join(", ") + "</p>";

    diseasePopup.style.display = "none";
});
document.addEventListener("click", (event) => {
    if (event.target !== openPopupButton && event.target !== diseasePopup && !diseasePopup.contains(event.target)) {
    diseasePopup.style.display = "none";
    }
});

// popup alergia
const selectAlergia = document.getElementById("selectAlergia");
const openPopupButtonAlergias = document.getElementById("openPopupButtonAlergias");
const alergiaPopup = document.getElementById("alergiaPopup");
const selectAlergiasButton = document.getElementById("selectAlergiasButton");
const selectedAlergiasArea = document.getElementById("selectedAlergias");

selectAlergia.addEventListener("change", () => {
    if (selectAlergia.value === "sim") {
    openPopupButtonAlergias.disabled = false;
    } else {
    openPopupButtonAlergias.disabled = true;
    }
});

openPopupButtonAlergias.addEventListener("click", () => {
    alergiaPopup.style.display = "block";
});

selectAlergiasButton.addEventListener("click", () => {
    const alergiasOptions = document.querySelectorAll("#alergiaOptions input[type=checkbox]:checked");
    const selectedAlergias = Array.from(alergiasOptions).map(option => option.value);

    selectedAlergiasArea.innerHTML = "<p>Alergias selecionadas: " + selectedAlergias.join(", ") + "</p>";

    alergiaPopup.style.display = "none";
});
document.addEventListener("click", (event) => {
    if (event.target !== openPopupButtonAlergias && event.target !== alergiaPopup && !alergiaPopup.contains(event.target)) {
    alergiaPopup.style.display = "none";
    }
});

// popup deficiência
const selectDefic = document.getElementById("selectDefic");
const openPopupButtonDefic = document.getElementById("openPopupButtonDefic");
const deficPopup = document.getElementById("deficPopup");
const selectDeficButton = document.getElementById("selectDeficButton");
const selectedDeficArea = document.getElementById("selectedDefic");

selectDefic.addEventListener("change", () => {
    if (selectDefic.value === "sim") {
    openPopupButtonDefic.disabled = false;
    } else {
    openPopupButtonDefic.disabled = true;
    }
});

openPopupButtonDefic.addEventListener("click", () => {
    deficPopup.style.display = "block";
});

selectDeficButton.addEventListener("click", () => {
    const deficOptions = document.querySelectorAll("#deficOptions input[type=checkbox]:checked");
    const selectedDefic = Array.from(deficOptions).map(option => option.value);

    selectedDeficArea.innerHTML = "<p>Deficiências selecionadas: " + selectedDefic.join(", ") + "</p>";

    deficPopup.style.display = "none";
});
document.addEventListener("click", (event) => {
    if (event.target !== openPopupButtonDefic && event.target !== deficPopup && !deficPopup.contains(event.target)) {
    deficPopup.style.display = "none";
    }
});

// popup convenio
const selectConvenio = document.getElementById("selectConvenio");
const openPopupButtonConvenio = document.getElementById("openPopupButtonConvenio");
const convenioPopup = document.getElementById("convenioPopup");
const selectConvenioButton = document.getElementById("selectConvenioButton");
const selectedConvenioArea = document.getElementById("selectedConvenio");

selectConvenio.addEventListener("change", () => {
    if (selectConvenio.value === "sim") {
    openPopupButtonConvenio.disabled = false;
    } else {
    openPopupButtonConvenio.disabled = true;
    }
});

openPopupButtonConvenio.addEventListener("click", () => {
    convenioPopup.style.display = "block";
});

selectConvenioButton.addEventListener("click", () => {
    const convenioOptions = document.querySelectorAll("#convenioOptions input[type=radio]:checked");
    const selectedConvenio = Array.from(convenioOptions).map(option => option.value);

    selectedConvenioArea.innerHTML = "<p>Convênio selecionado: " + selectedConvenio.join(", ") + "</p>";

    convenioPopup.style.display = "none";
});
document.addEventListener("click", (event) => {
    if (event.target !== openPopupButtonConvenio && event.target !== convenioPopup && !convenioPopup.contains(event.target)) {
    convenioPopup.style.display = "none";
    }
});

// popup medicamento
const selectMedicacao = document.getElementById("selectMedicacao");
const openPopupButtonMedicacao = document.getElementById("openPopupButtonMedicacao");
const medicacaoPopup = document.getElementById("medicacaoPopup");
const selectMedicacaoButton = document.getElementById("selectMedicacaoButton");
const selectedMedicacaoArea = document.getElementById("selectedMedicacao");

selectMedicacao.addEventListener("change", () => {
    if (selectMedicacao.value === "sim") {
    openPopupButtonMedicacao.disabled = false;
    } else {
    openPopupButtonMedicacao.disabled = true;
    }
});

openPopupButtonMedicacao.addEventListener("click", () => {
    medicacaoPopup.style.display = "block";
});

selectMedicacaoButton.addEventListener("click", () => {
    const medicacaoOptions = document.querySelectorAll("#medicacaoOptions input[type=checkbox]:checked");
    const selectedMedicacao = Array.from(medicacaoOptions).map(option => option.value);

    selectedMedicacaoArea.innerHTML = "<p>Deficiências selecionadas: " + selectedMedicacao.join(", ") + "</p>";

    medicacaoPopup.style.display = "none";
});
document.addEventListener("click", (event) => {
    if (event.target !== openPopupButtonMedicacao && event.target !== medicacaoPopup && !medicacaoPopup.contains(event.target)) {
    medicacaoPopup.style.display = "none";
    }
});

//recebe o elemento o elemento selecionado e o elemento botao para liberá-lo
function ativaBotaoPopup(select, buttonPopup){
    if (select.value === "sim") {
        buttonPopup.disabled = false;
        } else {
        buttonPopup.disabled = true;
        }
};

// Aguarde até que o documento esteja pronto
document.addEventListener('DOMContentLoaded', function () {
    // Obtenha uma referência ao switch
    var switchAtualizacoes = document.getElementById('switchAtualizacoes');
    
    // Obtenha uma lista de todos os campos atualizáveis
    var camposAtualizaveis = document.querySelectorAll('.campoAtualizavel');

    // Adicione um ouvinte de eventos para o switch
    switchAtualizacoes.addEventListener('change', function () {
        // Se o switch estiver marcado, habilite os campos, caso contrário, desabilite-os
        for (var i = 0; i < camposAtualizaveis.length; i++) {
            camposAtualizaveis[i].disabled = !switchAtualizacoes.checked;
            // Adicione um contorno cinza quando o campo estiver desativado
            camposAtualizaveis[i].style.outlineColor = camposAtualizaveis[i].disabled ? 'gray' : '';
        }
    });

    // Chame o evento de mudança para configurar o estado inicial
    switchAtualizacoes.dispatchEvent(new Event('change'));
});


function checkButton(status, openPopupButton) {
    openPopupButton.disabled = status.value !== "sim";
}

function checkSwitch(switchAtt, status, openPopupButton) {
    if (switchAtt.checked) {
        checkButton(status, openPopupButton);
    } else {
        openPopupButton.disabled = true;
    }
}

function converterTexto(input) {
    let texto = input.value;

    texto = texto.toUpperCase();

    input.value = texto;
}

document.addEventListener('DOMContentLoaded', function() {
    const selectDoenca = document.getElementById("selectDoenca");
    const openPopupButton = document.getElementById("openPopupButton");

    const selectAlergia = document.getElementById("selectAlergia");
    const openPopupButtonAlergias = document.getElementById("openPopupButtonAlergias");

    const selectDefic = document.getElementById("selectDefic");
    const openPopupButtonDefic = document.getElementById("openPopupButtonDefic");

    const selectConvenio = document.getElementById("selectConvenio");
    const openPopupButtonConvenio = document.getElementById("openPopupButtonConvenio");

    const selectMedicacao = document.getElementById("selectMedicacao");
    const openPopupButtonMedicacao = document.getElementById("openPopupButtonMedicacao");

    var switchAtt = document.getElementById("switchAtualizacoes");

    switchAtt.addEventListener('change', function() {
        checkSwitch(switchAtt, selectDoenca, openPopupButton);
        checkSwitch(switchAtt, selectAlergia, openPopupButtonAlergias);
        checkSwitch(switchAtt, selectDefic, openPopupButtonDefic);
        checkSwitch(switchAtt, selectConvenio, openPopupButtonConvenio);
        checkSwitch(switchAtt, selectMedicacao, openPopupButtonMedicacao);
    });

    // Chamada inicial para garantir que os botões estejam corretamente configurados quando a página é carregada
    checkSwitch(switchAtt, selectDoenca, openPopupButton);
    checkSwitch(switchAtt, selectAlergia, openPopupButtonAlergias);
    checkSwitch(switchAtt, selectDefic, openPopupButtonDefic);
    checkSwitch(switchAtt, selectConvenio, openPopupButtonConvenio);
    checkSwitch(switchAtt, selectMedicacao, openPopupButtonMedicacao);
});

function validarCPF(cpf) {
    var campoCpf = document.getElementById('campoCpf');
    var botaoAtualizar = document.getElementById('botaoAtualizar'); // Seleciona o botão de cadastro

    // Verifica se o CPF é válido
    var isValid = validaCPF(cpf);

    // Atualiza o elemento com a mensagem de validação
    if (isValid) {
        campoCpf.innerHTML = '<span style="color: green;">CPF - Válido</span>';
        botaoAtualizar.disabled = false; // Habilita o botão de cadastro
    } else {
        campoCpf.innerHTML = '<span style="color: red;">CPF - Inválido</span>';
        botaoAtualizar.disabled = true; // Desabilita o botão de cadastro
    }
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

document.addEventListener('DOMContentLoaded', function () {
    var nomeEmergInput = document.getElementById('nomeemerg');

    nomeEmergInput.addEventListener('input', function () {
        // Substitui todos os caracteres que não são letras ou espaços por uma string vazia
        var nomeEmergValue = nomeEmergInput.value.replace(/[^A-Za-z\s]/g, '');

        // Atualiza o valor do campo de entrada para conter apenas letras, espaços e convertido para maiúsculas
        nomeEmergInput.value = nomeEmergValue.toUpperCase();
    });

    // Encontrar o elemento de input de data de nascimento
    var dataNascimentoInput = document.getElementById('datanasc');
    
    // Encontrar o elemento de input de idade
    var idadeInput = document.querySelector('[name="idade_pessoa"]');

    // Adicionar um ouvinte de evento de teclado para o campo de idade
    idadeInput.addEventListener('keydown', function (event) {
        // Impedir a ação padrão do evento
        event.preventDefault();
    });
    
    // Adicionar um ouvinte de evento para calcular a idade quando a data de nascimento for alterada
    dataNascimentoInput.addEventListener('focusout', function () {
        // Obter a data de nascimento
        var dataNascimento = new Date(dataNascimentoInput.value);
        
        // Obter a data atual
        var dataAtual = new Date();
        
        // Calcular a diferença de tempo entre a data atual e a data de nascimento
        var diff = dataAtual.getTime() - dataNascimento.getTime();
        
        // Converter a diferença de tempo em idade
        var idade = Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
        
        // Preencher o campo de idade
        idadeInput.value = idade;
    });
});
