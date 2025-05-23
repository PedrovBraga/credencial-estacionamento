document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

document.addEventListener('DOMContentLoaded', function () {
    verificaCollapses();
    
    document.getElementById('form-cadastro').addEventListener('submit', function(event){
        event.preventDefault();

        // Seleciona o formulário
        const form = event.target;

        // Cria um objeto FormData a partir do formulário
        const formData = new FormData(form);

        // Converte os dados do FormData para um formato de URL (formato de formulário)
        const formEncoded = new URLSearchParams(formData).toString();

        var urlBase = form.action;
        $.ajax({
            type: 'post', 
            url: urlBase,
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

});

function populaCampos(beneficiario){
    console.log(beneficiario.NOME);
    document.getElementById('nome').value = beneficiario.NOME ?? '';
    document.getElementById('doc').value = beneficiario.CPF ?? '';
    
    if (beneficiario.DEF === 'S') {
        document.getElementById('collapsePNE').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapsePNE').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapsePNE').setAttribute('aria-expanded', 'true');
        document.getElementById('checkCollapsePNE').checked = true;
    }

    document.getElementById('nome_representante').value = beneficiario.NOMEREP ?? '';
    document.getElementById('rg_representante').value = beneficiario.RGREP ?? '';

}

function verificaCollapses(){
    if (document.getElementById('checkCollapsePNE').checked === true) {
        document.getElementById('collapsePNE').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapsePNE').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapsePNE').setAttribute('aria-expanded', 'true');
    }

    if (document.getElementById('checkCollapseSegVia').checked === true) {
        // Impede que o usuário altere o estado do checkbox
        document.getElementById('checkCollapseSegVia').addEventListener('click', (event) => {
            event.preventDefault(); // Impede a alteração do estado
            document.getElementById('checkCollapseSegVia').checked === true;
            document.getElementById('collapseSegVia').setAttribute('class', 'collapse show');
            document.getElementById('checkCollapseSegVia').setAttribute('class', 'form-check-input');
            document.getElementById('checkCollapseSegVia').setAttribute('aria-expanded', 'true');
        });
        document.getElementById('collapseSegVia').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapseSegVia').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapseSegVia').setAttribute('aria-expanded', 'true');
    }
}
