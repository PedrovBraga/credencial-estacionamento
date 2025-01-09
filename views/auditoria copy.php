
<!DOCTYPE html>
<html dir="ltr" lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Turmas</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/logo.png">
    <link href="../dist/css/style.css" rel="stylesheet">
    <!-- <link href="../dist/css/style_consulta_turmas.css" rel="stylesheet"> -->
    <style>
        @media print {

            /* Adicione estilos de impressão aqui */
            /* Por exemplo, para tabelas, você pode querer remover margens e padding */
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
        }

    </style>
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full"
        data-boxed-layout="full">
        <div class="page-wrapper">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Auditoria</h4>
                    </div>
                </div>
            </div>

            <div class="container-fluid">

                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Por Operador</h4>
                                <h5 class="card-subtitle">Insira o dado para sua consulta.</h5>
                                <form action="consulta_turmas.php" method="post">
                                    <div class="row gap-3">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Operador: </label>
                                                <select class="form-select shadow-none col-12" id="select-atividades"
                                                    name="atividade">
                                                    <option>Escolha uma opção...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="form-group mt-2" id="botao-busca">
                                            <button type="submit" class="btn btn-pref" id="buscar">Consultar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Por Munícipe</h4>
                                <h5 class="card-subtitle">Insira o dado para sua consulta.</h5>
                                <form action="consulta_turmas.php" method="post">
                                    <div class="row gap-3">
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <label>Nome do Munícipe: </label>
                                                <input type="text" id="telefone" class="w-100" name="telefone" placeholder="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-0">
                                        <div class="form-group mt-2" id="botao-busca">
                                            <button type="submit" class="btn btn-pref" id="buscar">Consultar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Operações</h4>
                                    <a class="btn btn-light btn-imprimir"
                                        href="processa_lista_chamada.php?atividade=">
                                        Exportar Lista Operações
                                    </a>
                            </div>
                            <div class="table-responsive" id="efetiva">
                                <table class="table table-striped" id="tb-efetiva">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Controle</th>
                                            <th scope="col">Operador</th>
                                            <th scope="col">Operação</th>
                                            <th scope="col">Data da Operação</th>
                                            <th scope="col">Nome Munícipe</th>
                                            <th scope="col">Credencial</th>
                                        </tr>
                                    </thead>
                                    <tbody class="lista-efetiva">
                                       
                                        <!-- // $contador = 0;
                                        // foreach ($dados_efetivos as $dado) {
                                        //     echo "
                                        //             <tr >
                                        //                 <td id='nome-efetivo-$contador'>" . $dado['nome_municipe'] . "</td>
                                        //                 <td id='cpf-efetivo-$contador'>" . $dado['cpf_municipe'] . "</td>
                                        //                 <td>" . $dado['telefone_municipe'] . "</td>
                                        //                 <td>" . $dado['data_matricula'] . "</td>
                                        //                 <td><button class='btn btn-light btn-desmatricular' id='btn-desmatricula-$contador' onclick='desmatricularDaTurma($contador)'>Desmatricular</button></td>
                                        //             </tr>
                                        //         ";
                                        //     $contador++;
                                        // } -->
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>

            </div>

        </div>

    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../dist/js/script_consulta_turmas.js"></script>
</body>

</html>