<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Array: nível de usuário => ["páginas restritas ao nível"]
$acessos_restritos = [
    2 => [
        "usuarios.php",
        "editar_usuario.php",
        "modalidade.php",
        "atividade.php",
        "relatorio_grafico",
        "processa_relatorio_pessoas",
        "processa_relatorio_planilhas",
        "/homologa"
    ],
];

function verificaURL($nivel, $url){
    global $acessos_restritos;

    if(isset($acessos_restritos[$nivel])){
        foreach($acessos_restritos[$nivel] as $url_restrita){
            if(strpos($url, $url_restrita) !== false){
                echo "<script>
                        alert('Acesso não permitido!');
                        window.location='/viva-vida/index.php';
                    </script>";
                exit;
            }
        }
    }
}

if(isset($_SESSION['id_login']) && isset($_SERVER['REQUEST_URI']) && isset($_SESSION['id_perfil'])){
    $nivel_usuario = $_SESSION['id_perfil'];
    $uri = $_SERVER['REQUEST_URI'];

    verificaURL($nivel_usuario, $uri);
} else {
    // Depuração: Verifica quais variáveis não estão definidas
    if (!isset($_SESSION['id_login'])) {
        echo "<script>alert('ID de usuário não definido.')</script>";
        // Redireciona para a página de login
        echo "<script>history.back()</script>";
        exit;
    }
    if (!isset($_SESSION['id_perfil'])) {
        echo "<script>alert('Nível de usuário não definido.')</script>";
        // Redireciona para a página de login
        echo "<script>history.back()</script>";
        exit;
    }
    if (!isset($_SERVER['REQUEST_URI'])) {
        echo "<script>alert('URL não definida.')</script>";
        // Redireciona para a página de login
        echo "<script>history.back()</script>";
        exit;
    }
}

?>