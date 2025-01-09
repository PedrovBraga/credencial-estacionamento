var esporteCheckbox;
var oficinaCheckboxes = [];

function gerenciarCheckboxSelecoes() {

    esporteCheckbox = null;
    oficinaCheckboxes = [];

    // Verificar cada checkbox
    $('input[name="atividades_selecionadas[]"]').each(function () {
        if ($(this).is(':checked')) {
            var modalidade = $(this).parent().text().split(' - ')[0].trim();

            // Verificar a modalidade 
            if (modalidade === 'ESPORTE') {
                if (esporteCheckbox) {
                    // Se outra atividade de esporte foi marcada, desmarcar a anterior
                    esporteCheckbox.prop('checked', false);
                }
                esporteCheckbox = $(this);
            } else if (modalidade === 'OFICINA') {
                if (oficinaCheckboxes.length === 2) {
                    // Se já existem 2 atividades de oficina marcadas, desmarcar a atual
                    $(this).prop('checked', false);
                } else {
                    // Adicionar à lista de checkboxes de oficina
                    oficinaCheckboxes.push($(this));
                }
            }
        }
    });

    // Atualizar a lista de atividades selecionadas
    atualizarAtividadesSelecionadas();
}

function atualizarAtividadesSelecionadas() {
    // Limpar seleções antigas
    $('#atividadesSelecionadas').empty();

    // Adicionar atividades selecionadas à lista
    if (esporteCheckbox) {
        $('#atividadesSelecionadas').append('<p>Esporte: ' + esporteCheckbox.parent().text().trim() + '</p>');
    }

    for (var i = 0; i < oficinaCheckboxes.length; i++) {
        $('#atividadesSelecionadas').append('<p>Oficina: ' + oficinaCheckboxes[i].parent().text().trim() + '</p>');
    }
}
