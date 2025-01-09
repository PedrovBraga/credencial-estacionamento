function recarregarPagina() {
        window.location.href = 'relatorio_grafico.php';
    }
    
    function filtrarGrafico(){

        var filtro1 = document.getElementById('filtro_1').value;
        var filtro2;
    
        if(filtro1 === 'municipe'){
            filtro2 = document.getElementById('filtro_municipe').value;
        } else if (filtro1 === 'publico'){
            filtro2 = document.getElementById('filtro_publico').value;
        } else filtro2 = document.getElementById('filtro_espera').value;
    
        var data1 = document.getElementById('data1').value;
        var data2 = document.getElementById('data2').value;

        if(data2 < data1){
            swalOptions = {
                icon: "error",
                title: "Data final deve ser maior que a inicial.",
                showCloseButton: false,
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    content: 'text-align-left'
                },
                html: `
                    <button class="btn btn-primary" onclick="recarregarPagina()">OK</button>
                `
            };

            Swal.fire(swalOptions);

        }
    
        var requestData = { filtro1: filtro1, filtro2: filtro2, data1: data1, data2: data2 };

        // console.log(filtro1 + ' - ' + filtro2 + ' - ' + data1 + ' - ' + data2);

        // Primeiro, carregue o pacote corechart
        google.charts.load('current', {'packages':['bar']});

        // Em seguida, defina o callback para ser chamado quando o pacote estiver carregado
        google.charts.setOnLoadCallback(function() {
            // Função para fazer a solicitação AJAX e desenhar o gráfico
            function fetchDataAndDrawChart() {
                $.ajax({
                    type: 'POST',
                    url: '../relatorio/processa_relatorio_grafico.php',
                    data: requestData,
                    success: function(data) {
                        // console.log("entrou\n\n");
                        var jsonData = $.parseJSON(data);
                        // console.log(jsonData);
                        
                        // Aqui, você só precisa converter seus dados para um DataTable quando a solicitação AJAX for bem-sucedida
                        var data_decode = google.visualization.arrayToDataTable(jsonData);

                        var options = {
                            title: 'titulo do gráfico',
                            legend: { position: 'right' },
                            colors: ['#5688CC', '#62CC56', '#5C4368', '#9DBD3A']
                        };

                        // Desenhe o gráfico aqui
                        const chart = new google.charts.Bar(document.getElementById('grafico'));
                        chart.draw(data_decode, options);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao obter dados da origem: ' + error);
                        alert("erro: " + error + " - " + status + " - " + JSON.stringify(xhr));
                    }
                });
            }

            // Agora, você pode chamar a função que faz a solicitação AJAX e desenha o gráfico
            fetchDataAndDrawChart();
        });
    };