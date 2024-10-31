

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    setTimeout(function() {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            initialDate: '2023-01-12',
            navLinks: true,
            selectable: true,
            editable: false,
            dayMaxEvents: 3,
        });

        calendar.render();

        // Ativação do calendário para evitar o erro de renderização
        calendar.today();

        // Marca no calendário as atividades
        adicionaAtividades(calendar);

        adicionaDadosDashboard();
    }, 300);  // Atraso de 300ms para garantir que o DOM esteja pronto
});
                    
function adicionaAtividades(calendar){
    $.ajax({
        type: 'post',
        url: './consulta/processa_atividades_calendario.php',
        success: function(response){
            var dataResponse = JSON.parse(response);
            console.log(dataResponse);

            var anoAtual = new Date().getFullYear().toString();
    
            dataResponse.forEach(element => {
                console.log(element.dias);
                calendar.addEvent({
                    title: element.nome,
                    // start: '2024-09-02T' + element.hora, // Data e hora de início
                    daysOfWeek: element.dias,
                    startRecur: '2024-09-02T' + element.hora,
                    endRecur: anoAtual + '-12-23',
                    duration: '01:00',
                    allDay: false  // Evento não é de dia inteiro
                  });
            });
    
        }, 
        error: function(){
            console.log('Erro no caminho!');
        }
    })
}

function adicionaDadosDashboard(calendar){
    $.ajax({
        type: 'post',
        url: './consulta/processa_dados_home.php',
        success: function(response){
            var dataResponse = JSON.parse(response);
            console.log(dataResponse);
    
            // Populando html matriculas
            document.getElementById('total-matriculas').innerText = dataResponse.total_matriculas;
            document.getElementById('matriculas-ativas').innerText = dataResponse.total_ativos_1;
            document.getElementById('matriculas-inativas').innerText = dataResponse.total_ativos_0;

            // Populando html pessoas
            document.getElementById('total-pessoas').innerText = dataResponse.total_municipes;
            document.getElementById('pessoas-ativas').innerText = dataResponse.municipes_sem_ativo_0;
            var pessoasInativas = parseInt(dataResponse.total_municipes) - parseInt(dataResponse.municipes_sem_ativo_0)
            document.getElementById('pessoas-inativas').innerText = pessoasInativas;

            // Populando html Atividades
            document.getElementById('total-turmas').innerText = dataResponse.total_atividades;

            // Populando html Lista de espera
            document.getElementById('total-espera-ativas').innerText = dataResponse.total_espera_ativas;
            document.getElementById('total-pessoas-espera').innerText = dataResponse.total_pessoas_espera;
            document.getElementById('total-espera-inativas').innerText = dataResponse.total_espera_inativas;
        }, 
        error: function(){
            console.log('Erro no caminho!');
        }
    })
}