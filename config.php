<?php

session_save_path("C:/php_sessions");
session_start(); 


require_once "funcoes.php";
// Configuração do banco de dados
$host = 'localhost';
$dbname = 'softtech_db';
$username = 'root'; // Altere para seu usuário do banco
$password = 'root'; // Altere para sua senha do banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
