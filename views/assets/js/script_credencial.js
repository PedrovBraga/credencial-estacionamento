document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

// consulta cep
document.addEventListener('DOMContentLoaded', function () {

    // verificaCollapses();

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
                    // console.log(response.beneficiario["\u0000*\u0000dados"]);
                    // populaCampos(response.beneficiario["\u0000*\u0000dados"], response.credencial["\u0000*\u0000dados"]); 
                    renderizaLista(response.beneficiario["\u0000*\u0000dados"], response.credencial);
                }
            },
            error: function(error, xhr){
                console.log(error, xhr);
            }
        })
    })
    
    document.getElementById('consultaRegistro').addEventListener('click', function(){
        
        const urlBase = this.dataset.url;
        const numRegistro = document.getElementById('registro_consulta').value;

        console.log(urlBase);
        $.ajax({
            type: 'post',
            url: urlBase,
            data: {
                registro: numRegistro
            },
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
            dataType: 'json', // Especifica o formato esperado da resposta
            success: function(response){
                if(response.status === 0){
                    console.log('mensagem: ' + response.mensagem);
                     
                } else {
                    console.log('mensagem: ' + response.mensagem); 
                    // console.log(response.beneficiario["\u0000*\u0000dados"]);
                    // populaCampos(response.beneficiario["\u0000*\u0000dados"], response.credencial["\u0000*\u0000dados"]); 
                    renderizaLista(response.beneficiario["\u0000*\u0000dados"], response.credencial["\u0000*\u0000dados"]);
                }
            },
            error: function(error, xhr){
                console.log(error, xhr);
            }
        })
    })

    // document.getElementById('form-cadastro').addEventListener('submit', function(event){
    //     event.preventDefault();

    //       // Seleciona o formulário
    //     const form = event.target;

    //     // Cria um objeto FormData a partir do formulário
    //     const formData = new FormData(form);

    //     // Converte os dados do FormData para um formato de URL (formato de formulário)
    //     const formEncoded = new URLSearchParams(formData).toString();

    //     var urlBase = form.action;
    //     $.ajax({
    //         type: 'post', 
    //         url: urlBase,
    //         data: formEncoded,
    //         contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
    //         dataType: 'json', // Especifica o formato esperado da resposta
    //         success: function(response){
    //              // Depuração para verificar a resposta recebida
    //             console.log(response);
    //             if(response.status === 0){
    //                 // console.log('mensagem: ' + response);
    //                 var alerta = new Alerta();
    //                 alerta.erro(response.mensagem).renderizar();
    //             } else {
    //                 // console.log('mensagem: ' + response); 
    //                 var alerta = new Alerta();
    //                 alerta.sucesso(response.mensagem).tratativa({urlConfirmar: response.urlConfirmar}).renderizar();
    //             }
    //         },
    //         error: function(error, xhr){
    //             console.log(error, xhr);
    //         }
    //     })
    // })
});

function renderizaLista(beneficiario, credencial){
    const lista = document.getElementById('lista');
    let html = '';

    credencial.forEach(cred => {
        html += `
            <tr class='pb-3'>
                <td>${cred["\u0000*\u0000dados"].REGISTRO}</td>
                <td>${cred["\u0000*\u0000dados"].ANO}</td>
                <td>${cred["\u0000*\u0000dados"].TIPO}</td>
                <td>${beneficiario.NOME}</td>
                <td>${new Date(cred["\u0000*\u0000dados"].VALIDADE) < new Date() ? 'VENCIDA' : 'ATIVA'}</td>
                <td>
                    <button>Editar</button>
                    <button>2ª via</button>
                </td>
            </tr>
        `;
    });

    lista.innerHTML = html;
}

function populaCampos(beneficiario, credencial){
    document.getElementById('registro').value = credencial.REGISTRO ?? '';
    document.getElementById('ano').value = credencial.ANO ?? '';
    document.getElementById('dtemissao').value = credencial.DTEMISSAO ? credencial.DTEMISSAO.split(' ')[0] : '';
    document.getElementById('validade').value = credencial.VALIDADE ? credencial.VALIDADE.split(' ')[0] : '';
    document.getElementById('nome').value = beneficiario.NOME ?? '';
    document.getElementById('cpf').value = beneficiario.CPF ?? '';
    document.getElementById('nome_operador').value = credencial.NOMEOPERADOR ?? '';
    document.getElementById('cargo_operador').value = credencial.CARGOOPERADOR ?? '';
    if (beneficiario.DEF === 'S') {
        document.getElementById('collapsePNE').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapsePNE').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapsePNE').setAttribute('aria-expanded', 'true');
        document.getElementById('checkCollapsePNE').checked = true;
    }
    document.getElementById('nome_representante').value = beneficiario.NOMEREP ?? '';
    document.getElementById('rg_representante').value = beneficiario.RGREP ?? '';
    document.getElementById('tipoRetirada').value = credencial.TIPORETIRADA ?? '';
    document.getElementById('retirada').value = credencial.RETIRADA ?? '';
    if (credencial.SEGVIA === 'S') {
        document.getElementById('collapseSegVia').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapseSegVia').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapseSegVia').setAttribute('aria-expanded', 'true');
        document.getElementById('checkCollapseSegVia').checked = true;
    }
    document.getElementById('obsSegVia').value = credencial.OBSSEGVIA ?? '';
    if (credencial.SEGVIACASSADA === 'S') {
        document.getElementById('collapseSegViaCassada').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapseSegViaCassada').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapseSegViaCassada').setAttribute('aria-expanded', 'true');
        document.getElementById('checkCollapseSegViaCassada').checked = true;
    }
    document.getElementById('obsCassada').value = credencial.OBSCASSADA ?? '';
}

function verificaCollapses(){
    if (document.getElementById('checkCollapsePNE').checked === true) {
        document.getElementById('collapsePNE').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapsePNE').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapsePNE').setAttribute('aria-expanded', 'true');
    }

    if (document.getElementById('checkCollapseSegVia').checked === true) {
        document.getElementById('collapseSegVia').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapseSegVia').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapseSegVia').setAttribute('aria-expanded', 'true');
    }

    if (document.getElementById('checkCollapseSegViaCassada').checked === true) {
        document.getElementById('collapseSegViaCassada').setAttribute('class', 'collapse show');
        document.getElementById('checkCollapseSegViaCassada').setAttribute('class', 'form-check-input');
        document.getElementById('checkCollapseSegViaCassada').setAttribute('aria-expanded', 'true');
    }
}
