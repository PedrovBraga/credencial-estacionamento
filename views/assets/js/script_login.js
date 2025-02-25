document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('form').addEventListener('submit', function(event){
            
        event.preventDefault();

        const urlBase = this.action;

        const usuario = document.getElementById('usuario').value;
        const senha = document.getElementById('senha').value;

        // Caso seja caminho "alterarSenha" então confere a confirmação de senha
        const senhaConfirmacaoDiv = document.getElementById('div-confirma-senha');

        // Verifica se o campo de confirmação de senha está visível (não tem a classe d-none)
        if (senhaConfirmacaoDiv && !senhaConfirmacaoDiv.classList.contains('d-none')) {
            const senhaConfirmacao = document.getElementById('confirma-senha');
            const senhaConfirmacaoValor = senhaConfirmacao.value.trim();

            if (senhaConfirmacaoValor === "" || senhaConfirmacaoValor !== senha) {
                document.getElementById('senha-invalida').style.display = 'block';
                return; // Interrompe o envio do formulário
            }
        }

        $.ajax({
            type: 'post',
            url: urlBase,
            data: {
                usuario: usuario,
                senha: senha
            },
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
            dataType: 'json', // Especifica o formato esperado da resposta
            success: function(response){
                if(response.status === 0){
                    // Login com senha incorreta
                    console.log('mensagem: ' + response.mensagem);
                    console.log(response);
                    document.getElementById('login-invalido').style.display = 'block';
                    document.getElementById('login-invalido').innerText = response.mensagem;
                    // form.classList.add('was-validated'); // Use a referência ao formulário diretamente
                } else {
                    if(response.status === 1){
                        // Login com senha correta
                        if(senha === '1234'){
                            window.location.href = 'usuario/alterar-senha?usuario=' + usuario;
                        } else {
                            console.log('mensagem: ' + response.mensagem); 
                            console.log(response);
                            window.location.href = response.urlConfirmar;
                        }
                    } 
                }
            },
            error: function(error, xhr){
                console.log(error, xhr);
                document.getElementById('login-invalido').style.display = 'block';
            }
        })
    });
});