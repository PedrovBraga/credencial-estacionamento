document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

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
                    var alerta = new Alerta();
                    alerta.erro(response.mensagem).renderizar();
                } else {
                    console.log('mensagem: ' + response.mensagem); 
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
                    var alerta = new Alerta();
                    alerta.erro(response.mensagem).renderizar();
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
});


function imprimirPagina(numRegistro) {
    
    $.ajax({
        type: 'GET',
        url: 'imprimir',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
        data: {
            registro: numRegistro
        },
        dataType: 'json', // Especifica o formato esperado da resposta
        success: function(response){
            if(response.status === 0){
                console.log('mensagem: ' + response.mensagem);
                var alerta = new Alerta();
                alerta.erro(response.mensagem).renderizar();
            } else {
                console.log('mensagem: ' + response.mensagem); 
                url = 'visualizar?registro=' + numRegistro;
                window.location.href = url;
            }
        },
        error: function(error, xhr){
            console.log(error, xhr);
        }
    })
}

function encaminharSegundaVia(numRegistro){
    $.ajax({
        type: 'GET',
        url: 'editar',
        data: {
            registro: numRegistro
        },
        success: function(response, status, xhr){
            const contentType = xhr.getResponseHeader('Content-Type');

            if (contentType && contentType.includes('application/json')) {
                if(response.status === 0){
                    console.log('mensagem: ' + response.mensagem);
                    var alerta = new Alerta();
                    alerta.erro(response.mensagem).renderizar();
                }
            }  else {
                // console.log('mensagem: ' + response.mensagem); 
                url = 'editar?registro=' + numRegistro;
                window.location.href = url;
            }
        },
        error: function(error, xhr){
            console.log(error, xhr);
        }
    })
}

function renderizaLista(beneficiario, credencial){
    const lista = document.getElementById('lista');
    let html = '';

    // console.log(credencial)

     // Garante que "credencial" seja sempre um array para iteração
     if (!Array.isArray(credencial)) {
        // Se for um objeto, converte para um array de um único elemento
        credencial = [credencial];
    }

    console.log(credencial);
    
    credencial.forEach(cred => {
        let registroPadronizado = cred["\u0000*\u0000dados"].REGISTRO + '/' + cred["\u0000*\u0000dados"].ANO;

        html += `
            <tr class='pb-3'>
                <td class="align-middle">${cred["\u0000*\u0000dados"].REGISTRO}</td>
                <td class="align-middle">${cred["\u0000*\u0000dados"].ANO}</td>
                <td class="align-middle">${cred["\u0000*\u0000dados"].TIPO}</td>
                <td class="align-middle">${beneficiario.NOME}</td>
                <td class="align-middle">${new Date(cred["\u0000*\u0000dados"].VALIDADE) < new Date() ? '<p class="text-danger">VENCIDA</p>' : '<p class="text-success">ATIVA</p>'}</td>
                <td class="align-middle">
                    <button class="btn btn-secondary" onclick="encaminharSegundaVia('${registroPadronizado}')"
                        ${new Date(cred["\u0000*\u0000dados"].VALIDADE) < new Date() ? 'disabled' : ''}>
                        2ª Via
                    </button>
                    <button class="btn btn-info text-white" onclick="imprimirPagina('${registroPadronizado}')" 
                         ${new Date(cred["\u0000*\u0000dados"].VALIDADE) < new Date() ? 'disabled' : ''}>
                        Imprimir
                    </button>
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
