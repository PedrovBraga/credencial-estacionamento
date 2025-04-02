document.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
    }
});

document.getElementById('btn-doc').addEventListener('click', function(){
    
    const urlBase = this.dataset.url;
    const numDoc = document.getElementById('input-doc').value;

    $.ajax({
        type: 'get',
        url: urlBase,
        data: {
            doc: numDoc
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
        dataType: 'json', // Especifica o formato esperado da resposta
        success: function(response){
            if(response.status === 0){
                console.log('mensagem: ' + response);
                var alerta = new Alerta();
                alerta.erro(response.mensagem).renderizar();
            } else {
                console.log('mensagem: ' + response); 
                renderizaLista(response.auditorias);
            }
        },
        error: function(error, xhr){
            console.log(error, xhr);
        }
    })
});

document.getElementById('btn-operador').addEventListener('click', function(){
    
    const urlBase = this.dataset.url;
    const operador = document.getElementById('select-operador').value;

    console.log(urlBase);
    $.ajax({
        type: 'get',
        url: urlBase,
        data: {
            operador: operador
        },
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Define o tipo de conteúdo
        dataType: 'json', // Especifica o formato esperado da resposta
        success: function(response){
            if(response.status === 0){
                console.log(response);
                var alerta = new Alerta();
                alerta.erro(response.mensagem).renderizar();
            } else {
                console.log(response); 
                renderizaLista(response.auditorias);
            }
        },
        error: function(error, xhr){
            console.log(error, xhr);
        }
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

function renderizaLista(auditorias){
    const lista = document.getElementById('lista');
    let html = '';

    // console.log(credencial)

     // Garante que "credencial" seja sempre um array para iteração
     if (!Array.isArray(auditorias)) {
        // Se for um objeto, converte para um array de um único elemento
        auditorias = [auditorias];
    }
    // console.log('auditorias: ', auditorias);
    auditorias.forEach(audit => {
        console.log('audit: ', audit);
        // audit.forEach(aud => {
            html += `
                <tr class='pb-3'>
                    <td class="align-middle">${audit["\u0000*\u0000dados"].ID}</td>
                    <td class="align-middle">${audit["\u0000*\u0000dados"].OPERADOR}</td>
                    <td class="align-middle">${audit["\u0000*\u0000dados"].OPERACAO}</td>
                    <td class="align-middle">${audit["\u0000*\u0000dados"].DT_OPERACAO}</td>
                    <td class="align-middle">${audit["\u0000*\u0000dados"].NOME_MUNICIPE}</td>
                    <td class="align-middle">${audit["\u0000*\u0000dados"].CREDENCIAL}</td>
                </tr>
            `;
        // })
    });

    lista.innerHTML = html;
}