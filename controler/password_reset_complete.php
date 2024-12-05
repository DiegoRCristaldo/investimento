<?php
require("../data/coneccao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = filter_var($_POST['id_usuario'], FILTER_SANITIZE_NUMBER_INT);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar se o id_usuario é válido
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE id = :id_usuario');
    $stmt->execute([':id_usuario' => $id_usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Atualizar a senha do usuário
        $stmtUpdate = $pdo->prepare('UPDATE usuarios SET senha = :senha WHERE id = :id_usuario');
        $updated = $stmtUpdate->execute([
            ':senha' => $password,
            ':id_usuario' => $id_usuario,
        ]);

        if ($updated) {
            // Apagar os códigos associados ao usuário
            $stmtDelete = $pdo->prepare('DELETE FROM recuperacao_senha WHERE id_usuario = :id_usuario');
            $stmtDelete->execute([':id_usuario' => $id_usuario]);

            echo 'Senha atualizada com sucesso.';
        } else {
            echo 'Erro ao atualizar a senha.';
        }
    } else {
        echo 'Usuário não encontrado.';
    }
}
?>
