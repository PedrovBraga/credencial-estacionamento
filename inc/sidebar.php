<?php
include ('conexao.php');
if (!isset($_SESSION)) {
    session_start();
}

// include ('verifica_login.php');
// if (!isset($_SESSION['id_perfil']) || $_SESSION['id_perfil'] != 1) {
//     echo '<style>#cadastro_usuario, #cadastro_modalidade, #cadastro_atividade, #relatorios { display: none; }</style>';
// }

// include('verifica_acesso.php');
?>

<style>
    .sidebar-nav ul .sidebar-item li.dropdown-item:hover .sidebar-link {
        color: black;
    }

    .sidebar-nav ul .sidebar-item li.dropdown-item .sidebar-link {
        padding: 0.65rem 2.2rem;
    }

</style>

<aside class="left-sidebar" data-sidebarbg="skin5">
    <div class="navbar-brand">
        <a href="" class="logo">
            <img src="../assets/images/logo-prefeitura-de-jacarei.png" id="logo-img" style="width: 170px; height: auto" alt="viva vida">
        </a>
    </div>
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">

                <!-- menu cadastros -->
                <li class="sidebar-item">
                    <!-- data-bs-toggle controla pela classe a visibilidade da ul e data-bs-target controla pelo id a "animação" do menu -->
                    <a class="sidebar-link waves-effect" type="button" data-bs-toggle="collapse" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="mdi mdi-account"></i>
                        <span class="hide-menu">Munícipe</span>
                    </a>
                    <!-- <ul class="collapse sidebar-item">
                        <li class="dropdown-item p-0 hide-menu" id="cadastro_usuario"><a class="sidebar-link waves-effect waves-dark" href="../usuarios.php">Cadastro</a></li>
                        <li class="dropdown-item p-0 hide-menu" id="cadastro_pessoa"><a class="sidebar-link waves-effect waves-dark" href="../pessoa.php">Munícipes</a></li>
                        <li class="dropdown-item p-0 hide-menu" id="cadastro_modalidade"><a class="sidebar-link waves-effect waves-dark" href="../modalidade.php">Modalidades</a></li>
                        <li class="dropdown-item p-0 hide-menu" id="cadastro_atividade"><a class="sidebar-link waves-effect waves-dark" href="../atividade.php">Atividades</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../realizar_matricula.php">Matrículas</a></li>
                    </ul> -->
                </li>

                <!-- menu consultas -->
                <li class="sidebar-item">
                    <!-- data-bs-toggle controla pela classe a visibilidade da ul e data-bs-target controla pelo id a "animação" do menu -->
                    <a class="sidebar-link waves-effect waves-dark" type="button" data-bs-toggle="collapse" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="mdi mdi-account-card-details"></i>
                        <span class="hide-menu">Credencial</span>
                    </a>
                    <!-- <ul class="collapse sidebar-item">
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../consulta/consulta_atestados.php">Atestados</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../consulta/consulta_municipe.php">Munícipes</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../consulta/consulta_modalidades.php">Modalidades</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../consulta/consulta_atividades.php">Atividades</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../consulta/consulta_turmas.php">Turmas</a></li>
                    </ul> -->
                </li>

                
                <li class="sidebar-item" id="acompanhamento">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../acompanhamento.php" aria-expanded="false">
                        <i class="mdi mdi-magnify"></i>
                        <span class="hide-menu">Auditoria</span>
                    </a>
                </li>
                
                <!-- menu relatórios -->
                <li class="sidebar-item" id="relatorios">
                    <!-- data-bs-toggle controla pela classe a visibilidade da ul e data-bs-target controla pelo id a "animação" do menu -->
                    <a class="sidebar-link waves-effect waves-dark" type="button" data-bs-toggle="collapse" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="mdi mdi-file-outline"></i>
                        <span class="hide-menu">Relatórios</span>
                    </a>
                    <ul class="collapse sidebar-item">
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../relatorio/relatorio_grafico.php">Gráficos</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../relatorio/processa_relatorio_planilha.php">Resumo</a></li>
                        <li class="dropdown-item p-0 hide-menu"><a class="sidebar-link waves-effect waves-dark" href="../relatorio/processa_relatorio_pessoas.php">Alunos</a></li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../logout.php"
                        aria-expanded="false">
                        <i class="mdi mdi-logout"></i>
                        <span class="hide-menu">LogOut</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>