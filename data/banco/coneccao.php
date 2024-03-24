<?php
    // Carregar variáveis de ambiente usando dotenv, se estiver usando
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Definir variáveis para conexão com o banco de dados
    $servidor = $_ENV['SERVIDOR'];
    $porta = $_ENV['PORTA'];
    $usuario = $_ENV['USUARIO'];
    $senha = $_ENV['SENHA'];
    $bd = $_ENV['BANCO'];
?>