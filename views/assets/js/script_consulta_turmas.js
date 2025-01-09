function recarregarPagina() {
    window.location.href = 'consulta_turmas.php';
}

const atividade = document.getElementById('select-atividades').value;

function desmatricularDaTurma(contador){
    //DESMATRICULAR DA TURMA
    var idCpf = 'cpf-efetivo-' + contador;
    var idNome = 'nome-efetivo-' + contador;

    const cpfEfetivo = document.getElementById(idCpf).innerText;
    const nomeEfetivo = document.getElementById(idNome).innerText;

    Swal.fire({
        title: "Deseja realmente desmatricular o munícipe " + nomeEfetivo + "?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#36D954",
        cancelButtonColor: "#D93654",
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // O usuário clicou em "Sim", proceder com a requisição AJAX
            $.ajax({
                type: "POST",
                url: 'processa_consulta_desmatricula.php',
                data: {
                    cpf: cpfEfetivo,
                    atividade: atividade
                },
                success: function (response) {
		    console.log(response);
                    var mensagem = JSON.parse(response);
                    var swalOptions = {};
    
                    if (mensagem && mensagem.mensagem) {
                        swalOptions = {
                            icon: mensagem.sucesso ? "success" : "error",
                            title: mensagem.mensagem,
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
                    } 
    
                    Swal.fire(swalOptions);
                },
                error: function (error) {
                    console.log('Erro na solicitação AJAX: ', error);
                }
            });
        }
    });
}


function matricularNaTurma(contador){
    //MATRICULAR NA TURMA
    var idCpf = 'cpf-espera-' + contador;
    var idNome = 'nome-espera-' + contador;

    const cpfEspera = document.getElementById(idCpf).innerText;
    const nomeEspera = document.getElementById(idNome).innerText;
    
    Swal.fire({
        title: "Deseja matricular o munícipe " + nomeEspera + "?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#36D954",
        cancelButtonColor: "#D93654",
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            // O usuário clicou em "Sim", proceder com a requisição AJAX
            $.ajax({
                type: "POST",
                url: 'processa_consulta_matricula.php',
                data: {
                    cpf: cpfEspera,
                    atividade: atividade
                },
                success: function (response) {
			console.log(response);
                    var mensagem = JSON.parse(response);
                    var swalOptions = {};
    
                    if (mensagem && mensagem.mensagem) {
                        swalOptions = {
                            icon: mensagem.sucesso ? "success" : "error",
                            title: mensagem.mensagem,
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
                    } 
    
                    Swal.fire(swalOptions);
                },
                error: function (error) {
                    console.log('Erro na solicitação AJAX: ', error);
                }
            });
        }
    });
}

// Função para formatar os dias da semana
function formatarDiasSemana(diasSemana) {
    var diasFormatados = diasSemana.match(/.{1,2}/g).map(function (dia) {
        switch (dia) {
            case '2a':
                return 'Segunda';
            case '3a':
                return 'Terça';
            case '4a':
                return 'Quarta';
            case '5a':
                return 'Quinta';
            case '6a':
                return 'Sexta';
            default:
                return dia;
        }
    });

    return diasFormatados.join('/');
}

// Função para formatar o horário
function formatarHorario(horario) {
    return horario.substring(0, 2) + ':' + horario.substring(2);
}

