<?php
require('../data/coneccao.php'); // Conexão com o banco de dados
require '../vendor/autoload.php'; // Autoload do PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dados = include('../data/dados.php');
$host = $dados['hostEmail'];
$username = $dados['userEmail'];
$password = $dados['passwordEmail'];
$port = $dados['portEmail'];

$pdo;

function __construct($pdo) {
    $this->pdo = $pdo; // Recebe conexão com o banco
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Verificar se o e-mail existe no banco e obter o ID do usuário
    $stmt = "SELECT id FROM usuarios WHERE e_mail = :email";
    $stmtVerifica = $pdo->prepare($stmt);
    $stmtVerifica->execute([':email' => $email]);
    $result = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

    if ($result) { // Verificar se o resultado não é falso
        $id_usuario = $result['id'];

        // Gerar um código aleatório de 6 dígitos
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Inserir o código na tabela `recuperacao_senha`
        $stmtInsert = $pdo->prepare('INSERT INTO recuperacao_senha (id_usuario, codigo) VALUES (:id_usuario, :codigo)');
        $stmtInsert->execute([
            ':id_usuario' => $id_usuario,
            ':codigo' => $codigo,
        ]);

        // Configuração do PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = $host; // Servidor SMTP (exemplo com Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = $username; // Seu e-mail
            $mail->Password = $password; // Sua senha ou App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = $port;

            // Remetente e destinatário
            $mail->setFrom('guiafinanceiro@guiadomercadofinanceiro.com.br', 'Guia do Mercado Financeiro');
            $mail->addAddress($email);

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de senha';
            /*$mail->Body = "Seu código de recuperação é: <b>$codigo</b><br>
            Para redefinir sua senha, clique no link abaixo:<br>
            <a href='http://guiadomercadofinanceiro.com/investimento/view/resetar_senha.php?id_usuario=$id_usuario'>Redefinir senha</a>";*/
            $mail->Body = "<h3>Seu código de recuperação é: <strong>$codigo</strong></h3>";
            $mail->AltBody = "<h4>Seu código de recuperação é: $codigo</h4>";

            $mail->send();
            header("Location: /investimento/view/resetar_senha.php?id_usuario=$id_usuario");
            exit;
        } catch (Exception $e) {
            echo "Falha ao enviar o e-mail. Erro: {$mail->ErrorInfo}";
        }
    } else {
        echo 'E-mail não encontrado.';
    }
}
?>
