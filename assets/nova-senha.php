<?php 
require('../data/coneccao.php'); // Conexão com o banco de dados

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\GenericProvider;

require '../vendor/autoload.php';

// Configuração do provedor OAuth
$provider = new GenericProvider([
    'clientId'                => 'SEU_CLIENT_ID',       // Substitua pelo Client ID do Azure
    'clientSecret'            => 'SEU_CLIENT_SECRET',   // Substitua pelo segredo do cliente
    'redirectUri'             => 'http://localhost/',   // Redirecione conforme configurado no Azure
    'urlAuthorize'            => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
    'urlAccessToken'          => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
    'urlResourceOwnerDetails' => '',
    'scopes'                  => ['https://graph.microsoft.com/.default']
]);

try {
    // Obter token de acesso
    $accessToken = $provider->getAccessToken('client_credentials');

    // Configuração do PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->OAuth = [
        'provider' => $provider,
        'clientId' => 'SEU_CLIENT_ID',
        'clientSecret' => 'SEU_CLIENT_SECRET',
        'refreshToken' => 'SEU_REFRESH_TOKEN',
        'accessToken' => $accessToken->getToken()
    ];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configuração do e-mail
    $mail->setFrom('SEU_EMAIL', 'Seu Nome');
    $mail->addAddress('destinatario@exemplo.com');
    $mail->Subject = 'Teste de E-mail OAuth';
    $mail->Body = 'Este é um e-mail de teste enviado com PHPMailer e OAuth 2.0.';

    $mail->send();
    echo "E-mail enviado com sucesso!";
} catch (Exception $e) {
    echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    echo "Erro no OAuth: {$e->getMessage()}";
}

$erroMensagem = "";
$sucessoMensagem = "";
$etapa = 1; // Etapa da recuperação de senha (1 = inserir e-mail, 2 = verificar código)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Etapa 1: Inserir e-mail
    if ($etapa === 1) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erroMensagem = "E-mail inválido. Por favor, insira um endereço de e-mail válido.";
        } else {
            // Verificar se o e-mail existe na tabela `usuarios`
            $sql = "SELECT id, e_mail FROM usuarios WHERE e_mail = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                $erroMensagem = "E-mail não encontrado. Verifique e tente novamente.";
            } else {
                // Gerar código de recuperação e armazenar na tabela `recuperacao_senha`
                $codigoRecuperacao = rand(100000, 999999);

                // Remover códigos anteriores para este usuário (opcional)
                $sqlDelete = "DELETE FROM recuperacao_senha WHERE id_usuario = :id_usuario";
                $stmtDelete = $pdo->prepare($sqlDelete);
                $stmtDelete->execute([':id_usuario' => $usuario['id']]);

                // Inserir novo código
                $sqlInsert = "INSERT INTO recuperacao_senha (id_usuario, codigo) VALUES (:id_usuario, :codigo)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':id_usuario' => $usuario['id'],
                    ':codigo' => $codigoRecuperacao,
                ]);

                // Enviar o código de recuperação para o e-mail do usuário
                if (enviarEmail($usuario['e_mail'], $codigoRecuperacao)) {
                    $sucessoMensagem = "Um código de recuperação foi enviado para o e-mail informado.";
                    $etapa = 2; // Alterar para etapa de verificação
                } else {
                    $erroMensagem = "Não foi possível enviar o código de recuperação. Tente novamente mais tarde.";
                }
            }
        }
    }

    // Etapa 2: Verificar código
    if ($etapa === 2) {
        $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);

        // Verificar o código no banco (na tabela `recuperacao_senha`)
        $sql = "SELECT * FROM recuperacao_senha WHERE codigo = :codigo AND id_usuario = (SELECT id FROM usuarios WHERE e_mail = :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email, ':codigo' => $codigo]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            $erroMensagem = "Código inválido. Verifique e tente novamente.";
        } else {
            // Gerar nova senha
            $novaSenha = substr(md5(time()), 0, 8); // Gerar senha aleatória de 8 caracteres
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            // Atualizar a senha no banco de dados
            $sqlUpdate = "UPDATE usuarios SET senha = :senha WHERE e_mail = :email";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([':senha' => $senhaHash, ':email' => $email]);

            // Enviar a nova senha por e-mail
            if (enviarEmail($email, "Sua nova senha é: $novaSenha")) {
                header("Location: /investimento/login.php?recuperacao=sucesso");
                exit;
            } else {
                $erroMensagem = "Erro ao enviar a nova senha. Por favor, tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Nova Senha - Guia do Mercado Financeiro</title>
</head>
<body class="cadastro">
    <header class="cabecalho">
        <nav>
            <a href="../index.php"><h1 class="titulo">Guia do Mercado Financeiro</h1></a>
        </nav>
    </header>
    <main>
        <section>
            <form class="formulario" method="post">
            <?php if (!empty($erroMensagem)): ?>
                <p class="erro" style="color: red;"><?= htmlspecialchars($erroMensagem); ?></p>
            <?php endif; ?>

            <?php if (!empty($sucessoMensagem)): ?>
                <p class="sucesso" style="color: green;"><?= htmlspecialchars($sucessoMensagem); ?></p>
            <?php endif; ?>

            <?php if ($etapa === 1): ?>
                <label for="email"></label>
                <input class="layout" type="email" name="email" id="email" placeholder="E-mail" value="<?= htmlspecialchars($email ?? '') ?>" required>
            <?php elseif ($etapa === 2): ?>
                <label for="codigo"></label>
                <input class="layout" type="text" name="codigo" id="codigo" placeholder="Código de confirmação" required>
                <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
            <?php endif; ?>

            <button class="layout" type="submit">
                <?= $etapa === 1 ? 'Enviar Código' : 'Verificar Código' ?>
            </button>
            </form>
        </section>
    </main>
    <footer>
        <cite>
          <a href="https://rcdesenvolvimentodigital.com.br">
            &copy;2024 Guia do Investidor. Todos os direitos reservados. 
            Site desenvolvido por R Cristaldo Desenvolvimento Digital.
          </a>
        </cite>
    </footer>
</body>
</html>
