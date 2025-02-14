function desativar(event, usuario) {
    event.preventDefault();  // Previne o comportamento padrão do link

    // Pega o valor de href do elemento clicado
    const urlDesativar = event.target.href;  // Aqui pegamos o href do link clicado
    
    var alerta = new Alerta().questiona('Deseja realmente desativar o usuário ' + usuario + '?');

    Swal.fire({
        title: alerta.titulo,
        text: alerta.texto,
        icon: alerta.icon,
        showCancelButton: alerta.showCancelButton,
        cancelButtonText: alerta.textBtnCancel,
        confirmButtonText: "Confirmar", // Esse é o botão Confirmar
    }).then((result) => {
        if (result.isConfirmed) {
            // Quando o botão "Confirmar" for clicado, chame a função para desativar o usuário
            desativarUsuario(usuario, urlDesativar);
        } else {
            // Se o botão Cancelar for clicado, você pode fazer algo aqui, ou apenas ignorar
            console.log("Cancelado");
        }
    });
}

// Essa função será chamada após a confirmação do alerta
function desativarUsuario(usuario, urlDesativar) {
    // Chama o AJAX para desativar o usuário
    $.ajax({
        url: urlDesativar,
        type: 'POST',
        data: {
            usuario: usuario
        },
        success: function(response) {
            // Verifica a resposta do servidor
            if (response.status === 0) {
                // Caso ocorra algum erro ao desativar, exibe um alerta de erro
                var alerta = new Alerta();
                alerta.erro(response.mensagem).renderizar();
                
                // Caso precise atualizar a interface, pode remover o usuário da lista aqui
            } else {
                // Se desativado com sucesso, exibe um alerta de sucesso
                var alerta = new Alerta();
                alerta.sucesso(response.mensagem, {urlConfirmar: response.urlConfirmar}).renderizar();
            }
        },
        error: function(error) {
            console.log(error);
            // Caso ocorra um erro com o AJAX, exibe o alerta de erro
            var alerta = new Alerta();
            alerta.erro('Ocorreu um ERRO ao desativar o usuário.').renderizar();
        }
    });
}