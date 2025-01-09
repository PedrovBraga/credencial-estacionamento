var esporteCheckbox;
var oficinaCheckboxes = [];

$(document).ready(function () {

    $(document).on('change', 'input[name="atividades_selecionadas[]"]', function () {
        gerenciarCheckboxSelecoes();
    });
});

var cpf_municipe;

function matricular() {
    // Obtenha o ID do munícipe e as atividades selecionadas (certifique-se de ter esses valores disponíveis no seu formulário)
    cpf_municipe = document.getElementById('cpf').value; // Adicione aqui a lógica para obter o ID do municipe
    var atividades_selecionadas = [];

    // Obtenha as atividades selecionadas
    $('input[name="atividades_selecionadas[]"]:checked').each(function() {
        atividades_selecionadas.push($(this).val());
    });

   // alert(atividades_selecionadas);

    // Verifique se pelo menos uma atividade foi selecionada
    if (atividades_selecionadas.length === 0) {
        alert('Selecione pelo menos uma atividade para matricular.');
        return;
    }

    // Faça uma solicitação AJAX para processar a matrícula
    $.ajax({
        type: 'POST',
        url: 'processa_matricula.php',
        data: {
            cpf_municipe: cpf_municipe,
            atividades_selecionadas: atividades_selecionadas
        },
        success: function (response) {
            var mensagem = JSON.parse(response);
            var swalOptions = {};

            if (mensagem && mensagem.mensagem && mensagem.mensagem.includes('Vagas esgotadas')) {
                swalOptions = {
                    icon: mensagem.sucesso ? "success" : "error",
                    title: mensagem.mensagem,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        content: 'text-align-left'
                    },
                    html: `
                        <button class="btn btn-primary" onclick="outraMatricula('`+cpf_municipe+`')">Escolher Outra Atividade</button>
                        <button class="btn btn-primary" onclick="adicionarListaEspera()">Adicionar à Lista de Espera</button>
                    `
                };
            } else if (mensagem && mensagem.mensagem && mensagem.mensagem.includes('está matriculado')) {
                swalOptions = {
                    icon: mensagem.sucesso ? "success" : "error",
                    title: mensagem.mensagem,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        content: 'text-align-left'
                    },
                    html: `
                        <button class="btn btn-primary" onclick="fecharSweetAlert()">Escolher Outra Atividade</button>
                    `
                };
            } else if (mensagem && mensagem.mensagem && mensagem.mensagem.includes('Não é permitida')) {
                swalOptions = {
                    icon: mensagem.sucesso ? "success" : "error",
                    title: mensagem.mensagem,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        content: 'text-align-left'
                    },
                    html: `
                        <button class="btn btn-primary" onclick="fecharSweetAlert()">Escolher Outra Atividade</button>
                    `
                };
            } else if (mensagem && mensagem.mensagem && mensagem.mensagem.includes('É possível removê-lo')) {
                swalOptions = {
                    icon: mensagem.sucesso ? "success" : "error",
                    title: mensagem.mensagem,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        content: 'text-align-left'
                    },
                    html: `
                        <button class="btn btn-primary" onclick="fecharSweetAlert()">Escolher Outra Atividade</button>
                        `
                        // <button class="btn btn-primary" onclick="removerDaListaDeEspera()">Remover da Lista de Espera</button>
                };
            } else {
                swalOptions = {
                    icon: mensagem.sucesso ? "success" : "error",
                    title: mensagem.mensagem,
                    showCloseButton: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        content: 'text-align-left'
                    },
                    html: `
                        <button class="btn btn-primary" onclick="outraMatricula('`+cpf_municipe+`')">Matrícula Com Mesmo CPF</button>
                        <button class="btn btn-primary" onclick="recarregarPagina()">Matricula Com Novo CPF</button>
                    `
                };
            }

            Swal.fire(swalOptions);
        },
        error: function (error) {
            console.log('Erro na solicitação AJAX: ', error);
        }
    });
}

function outraMatricula(cpf_municipe) {
    window.location.href = 'realizar_matricula.php?cpf=' + encodeURIComponent(cpf_municipe);
}

function recarregarPagina() {
    window.location.href = 'realizar_matricula.php';
}

function fecharSweetAlert() {
    Swal.close();
}

// function removerDaListaDeEspera() {
//     window.location.href = 'realizar_remocao.php?cpf=' + encodeURIComponent(cpf_municipe);
// }

function adicionarListaEspera() {
    var cpf_municipe = document.getElementById('cpf').value;
    var atividades_selecionadas = [];
    $('input[name="atividades_selecionadas[]"]:checked').each(function() {
        atividades_selecionadas.push($(this).val());
    });

    // Faça uma solicitação AJAX para adicionar à lista de espera
    $.ajax({
        type: 'POST',
        url: 'adicionar_lista_espera.php',
        data: {
            cpf_municipe: cpf_municipe,
            atividades_selecionadas: atividades_selecionadas
        },
        success: function (response) {
            // Manipule a resposta conforme necessário
            var mensagem = JSON.parse(response);
            Swal.fire({
                icon: mensagem.sucesso ? "success" : "error",
                title: mensagem.mensagem,
                didClose: () => {
                    window.location.href = 'realizar_matricula.php';
                }
            });
        },
        error: function (error) {
            console.log('Erro na solicitação AJAX para adicionar à lista de espera: ', error);
        }
    });
}