<?php
require("../data/coneccao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = filter_var($_POST['id_usuario'], FILTER_SANITIZE_NUMBER_INT);
    $codigo = filter_var($_POST['codigo'], FILTER_SANITIZE_STRING);

    // Verificar se o código existe e se está dentro do prazo de 15 minutos
    $stmt = 'SELECT * FROM recuperacao_senha WHERE id_usuario = :id_usuario AND codigo = :codigo 
    AND criado_em > NOW() - INTERVAL 15 MINUTE';
    $stmtVerifica = $pdo->prepare($stmt);
    $stmtVerifica->execute([
            ':id_usuario' => $id_usuario, 
            ':codigo' => $codigo
    ]);
    $result = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Código válido - Prossegue para redefinir a senha
        header("Location: /investimento/view/nova_senha.php?id_usuario=$id_usuario");
        exit;
    } else {
        echo 'Código inválido ou expirado.';
    }
}
?>
