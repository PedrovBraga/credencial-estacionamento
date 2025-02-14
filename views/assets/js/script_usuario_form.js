document.getElementById('form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Seleciona o formulário
    const form = event.target;

    // Cria um objeto FormData a partir do formulário
    const formData = new FormData(form);

    // Converte os dados do FormData para um formato de URL (formato de formulário)
    const formEncoded = new URLSearchParams(formData).toString();

    var urlBase = form.action;

    $.ajax({
        url: urlBase,
        type: 'POST',
        data: formEncoded,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
        dataType: 'json', // Especifica o formato esperado da resposta
        success: function(response) {
            console.log(response);
            if (response.status === 0) {
                var alerta = new Alerta();
                alerta.erro(response.mensagem).renderizar();
            } else {
                var alerta = new Alerta();
                alerta.sucesso(response.mensagem, {urlConfirmar: response.urlConfirmar}).renderizar();
            }
        }
    });
});