<?php

$dados = include('dados.php');

$servidor = $dados['servidor'];
$usuario = $dados['usuario'];
$senha = $dados['senha'];
$banco = $dados['banco'];


try{
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Erro " . $e->getMessage();
}
