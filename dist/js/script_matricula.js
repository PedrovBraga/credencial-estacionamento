$(document).ready(function () {
    $('#cpf').inputmask("999.999.999-99", { "placeholder": "000.000.000-00" });

    atualizarAtividadesSelecionadas();
});

function desabilitarBotaoMatricular(desabilitar) {
    var botaoMatricular = $('#btnMatricular');
    botaoMatricular.prop('disabled', desabilitar);
}

function atualizarAtividadesSelecionadas() {
    // Limpe a lista de atividades selecionadas
    $('#atividadesSelecionadas').empty();

    // Obtenha todas as checkboxes marcadas
    var checkboxesSelecionadas = $('input[name="atividades_selecionadas[]"]:checked');
    var modalidade = document.getElementById('modalidade');

    if (modalidade.value == 'OFICINA' && checkboxesSelecionadas.length > 2) {
        $('#atividadesSelecionadas').append('<p>Selecione apenas UMA oficina</p>');
        desabilitarBotaoMatricular(true);
    } else if (modalidade.value == 'ESPORTE' && checkboxesSelecionadas.length > 1) {
        $('#atividadesSelecionadas').append('<p>Selecione apenas UM esporte</p>');
        desabilitarBotaoMatricular(true);
    } else if (modalidade.value == '--') {
        desabilitarBotaoMatricular(true);
    } else {
        checkboxesSelecionadas.each(function() {
            var atividadeSelecionada = $(this).closest('div').find('label').text();
            $('#atividadesSelecionadas').append('<p>' + atividadeSelecionada + '</p>');
        });
        desabilitarBotaoMatricular(false);
    }
}