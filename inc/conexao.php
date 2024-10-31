<?php
//  $servername = "webapps-db";
//  $username = "vivavida";
//  $password = "Vivavida_pmj@2023"; // Deixe em branco se não houver senha definida
//  $dbname = "vivavida"; // Nome do banco de dados que você deseja conectar

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'vivavida';

$dbhandle = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($dbhandle->connect_error) {
    die("Falha na conexão: " . $dbhandle->connect_error);
}
?>