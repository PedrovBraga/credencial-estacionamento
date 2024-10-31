<?php
include('../inc/conexao.php');
if (!isset($_SESSION)) {
    session_start();
}
// include('inc/verifica_login.php');
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/niceadmin-lite/" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/logo.png">
    <link href="../dist/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-body">
                            <h2>Usuários</h2>
                            <a href="adicionar_usuario.php" class="btn btn-editar" id="btn-add-user"
                                style="width: 10rem;">
                                <i class="mdi mdi-account-plus"></i> Adicionar Usuário
                            </a>
                            <table style="margin-top: 15px;">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Nome</th>
                                        <th>Matrícula</th>
                                        <th>Perfil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                            <p class="d-inline-flex gap-1" style="margin-top: 30px;">
                                <button class="btn btn-editar" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample">
                                    Usuários Desativados
                                </button>
                            </p>
                            <div class="collapse" id="collapseExample">
                                <table id="hiddenTable" class="slide-down" style="width: 62.3rem;">
                                    <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Nome</th>
                                            <th>Matrícula</th>
                                            <th>Perfil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?php
            include('../inc/footer.php');
            ?>
        </div>
    </div>

    <script>
        function toggleTable() {
            console.log('teste');
            var table = document.getElementById('hiddenTable');
            $(table).toggleClass('slide-down');
        }

        function recarregarPagina() {
            window.location.href = 'usuarios.php';
        }

        function removerUsuario(index) {
            console.log(index);
            Swal.fire({
                title: 'Deseja realmente remover este usuário?',
                text: 'Esta ação não pode ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Remover',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'remover_usuario.php',
                        data: { index: index },
                        success: function (response) {
                            var mensagem = JSON.parse(response);
                            var swalOptions = {};

                            if (mensagem && mensagem.mensagem && mensagem.mensagem.includes('com sucesso')) {
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
                                        <button class="btn btn-editar" onclick="recarregarPagina()">OK</button>
                                    `
                                };
                            }
                            Swal.fire(swalOptions);
                        },
                        error: function (error) {
                            console.log('Erro na solicitação AJAX:', error);
                        }
                    });
                }
            });
        }

        function reativarUsuario(index) {
            console.log(index);
            Swal.fire({
                title: 'Deseja reativar este usuário?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reativar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: 'reativar_usuario.php',
                        data: { index: index },
                        success: function (response) {
                            var mensagem = JSON.parse(response);
                            var swalOptions = {};

                            if (mensagem && mensagem.mensagem && mensagem.mensagem.includes('com sucesso')) {
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
                                        <button class="btn btn-editar" onclick="recarregarPagina()">OK</button>
                                    `
                                };
                            }
                            Swal.fire(swalOptions);
                        },
                        error: function (error) {
                            console.log('Erro na solicitação AJAX:', error);
                        }
                    });
                }
            });
        }
    </script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="../assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="../dist/js/waves.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    <script src="../dist/js/custom.min.js"></script>
</body>

</html>