<?php
    include('../inc/conexao.php');
    if (!isset($_SESSION)) {
        session_start();
    }
    // include('../inc/verifica_login.php');
?>

<!DOCTYPE html>
<html dir="ltr" lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Nice lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Nice admin lite design, Nice admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Nice Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Cadastrar Pessoa</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/niceadmin-lite/" />
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/logo.png">
    <link href="../dist/css/style.css" rel="stylesheet">
    <!-- <link href="../dist/css/style_pessoa.css" rel="stylesheet"> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        <?php
        include('../inc/sidebar.php');
        ?>
        <div class="page-wrapper px-5 w-100">
            <div class="page-breadcrumb pl-0 mt-3 mb-4">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Credencial</h4>
                    </div>
                </div>
            </div>
            <form class="form-cadastro" method="post" enctype="multipart/form-data" action="../cadastro_pessoa.php">

                <div class="row my-2 space-between">
                    <div class="input-group pl-0 col-md-6">
                        <div class="input-group col-md-4">
                            <label for="profissao" class="form-label">Nº Registro</label>
                            <input type="text" class="form-control w-100" id="registro" name="registro" placeholder="" required>
                        </div>
                        <div class="input-group col-md-4">
                            <label for="profissao" class="form-label">Ano</label>
                            <input type="number" class="form-control w-100" id="ano" name="ano" required>
                        </div>
                    </div>
                    <div class="input-group col-md-6">
                        <div class="input-group col-md-4">
                            <label for="data_emissao" id="label_data">Data de Emissão:</label>
                            <input type="date" id="data_emissao" name="data_emissao"
                                class="form-control w-100" readonly>
                        </div>
                        <div class="input-group col-md-4">
                            <label for="data_validade" id="label_data">Data de Validade:</label>
                            <input type="date" id="data_validade" name="data_validade"
                                class="form-control w-100" readonly>
                        </div>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="input-group col-md-4">
                        <label for="nomecompleto" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control w-100" id="nomecompleto" name="nomecompleto"
                            oninput="converterTexto(this)" placeholder="ex: Ana Paula Martins" required>
                    </div>
                    <div class="input-group col-md-2">
                        <label>Doc. Indentificação (CPF/RG)</label>
                        <input type="text" id="num_rg" class="form-control w-100" name="rg_pessoa"
                            placeholder="" required>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="input-group col-md-4">
                        <label for="nome_emissor" class="form-label">Nome Emissor</label>
                        <input type="text" class="form-control w-100" id="nome_emissor" name="nome_emissor"
                            oninput="converterTexto(this)" placeholder="" required>
                    </div>
                    <div class="input-group col-md-2">
                        <label>Cargo</label>
                        <input type="text" id="cargo" class="form-control w-100" name="cargo"
                            placeholder="" required>
                    </div>
                </div>
                <div class="row my-2 justify-content-between">
                    <div class="input-group col-md-3" style="margin-top: 30px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" data-bs-toggle="collapsePNE"
                                data-bs-target="#collapseExamplePNE" aria-expanded="false"
                                aria-controls="collapseExamplePNE" readonly>
                            <label class="form-check-label" for="flexCheckDefault">
                                Portador de Necessidades Especiais
                            </label>
                        </div>
                    </div>
                    <!-- <div class="input-group col-md-1" style="margin-top: 30px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false"
                                aria-controls="collapseExample">
                            <label class="form-check-label" for="flexCheckDefault">
                                2ª Via
                            </label>
                        </div>
                    </div> -->
                    <div class="input-group col-md-2">
                        <label class="form-label" for="inputGroupSelect01">Forma de Retirada da Credencial</label>
                        <select class="form-control w-100" id="inputGroupSelect01">
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="input-group col-md-2">
                        <label class="form-label" for="inputGroupSelect01">Credencial Retirada</label>
                        <select class="form-control w-100" id="inputGroupSelect01">
                            <option selected>Choose...</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>

                <!-- Verificar se já existe registro para definir btn / Deixar campos readonly tbm -->
                <div>
                    <div class="input-group col-md-1 pl-0" style="margin-top: 30px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false"
                                aria-controls="collapseExample">
                            <label class="form-check-label" for="flexCheckDefault">
                                2ª Via
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="input-group col-md-1 pl-0" style="margin-top: 30px;">
                        <div class="form-check">
                            <button type="submit" class="btn btn-pref" id="botaoCadastro">Gerar Credencial</button>
                        </div>
                    </div>
                </div>

                <div class="collapsePNE" id="collapseExamplePNE">
                    <hr class="my-4">
    
                    <h5 class="mb-4">Dados do Representante</h5>
                    <div class="row my-1">
                        <div class="input-group col-md-5">
                            <label for="profissao" class="form-label">Nome do Representante</label>
                            <input type="text" class="form-control w-100" id="profissao" name="profissao" placeholder="Nome" required>
                        </div>
                        <div class="input-group col-md-2">
                            <label>Doc. Indentificação (CPF/RG)</label>
                            <input type="text" id="num_rg" class="form-control w-100" name="rg_pessoa"
                                placeholder="" required>
                        </div>
                    </div>
                </div>

                <div class="collapse" id="collapseExample">
                    <!-- -->
                    <hr class="my-4">
                    <!-- form PNE -->
                    <div class="row my-1">
                        <div class="input-group col-md-6">
                            <label for="form-label">Observação 2ª via:</label>
                            <textarea class="form-control w-100" style="height: 100px;" aria-label="With textarea"></textarea>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                    <button type="submit" class="btn btn-pref" id="botaoCadastro">Emitir 2ª Via</button>
                </div>
                </div>

                <?php
                if (isset($_GET['mensagem']) && !empty($_GET['mensagem'])) {
                    $mensagem = $_GET['mensagem'];
                    echo '<script>
                            Swal.fire({
                                icon: "success",
                                title: "' . $mensagem . '",
                                didClose: () => {
                                    window.location.href = "pessoa.php";
                                }
                            });
                        </script>';
                }

                if (isset($_GET['erro']) && !empty($_GET['erro'])) {
                    $mensagem = $_GET['erro'];
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "' . $mensagem . '",
                                didClose: () => {
                                    window.location.href = "pessoa.php";
                                }
                            });
                        </script>';
                }
                ?>
            </form>
            <?php
            include('../inc/footer.php');
            ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('.form-cadastro');
            var fileInput = document.getElementById('fileInput');

            form.addEventListener('submit', function (event) {
                // Verifica se um arquivo foi selecionado
                if (fileInput.files.length > 0) {
                    var arquivo = fileInput.files[0];
                    // Verifica o tamanho do arquivo (2MB)
                    if (arquivo.size > 2 * 1024 * 1024) {
                        // Se o arquivo for muito grande, impede o envio do formulário
                        Swal.fire({
                            icon: "error",
                            title: "Erro ao fazer o upload do atestado! Selecione um arquivo com menos de 2MB" ,
                            didClose: () => {
                                
                            } 
                        })
                        event.preventDefault();
                    }
                }
            });
        });
    </script>
    <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var campoData = document.getElementById('data_cadastro');
                        var campoHora = document.getElementById('hora_cadastro');

                        var dataAtual = new Date();
                        var dataFormatada = dataAtual.toISOString().split('T')[0];
                        campoData.value = dataFormatada;

                        var horaAtual = dataAtual.getHours().toString().padStart(2, '0') + ':' + dataAtual.getMinutes().toString().padStart(2, '0');
                        campoHora.value = horaAtual;
                    });
                </script>

    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="../dist/js/script_pessoa.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="../dist/js/waves.js"></script>
    <script src="../dist/js/custom.min.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
</body>

</html>