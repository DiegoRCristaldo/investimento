<?php
require('../data/coneccao.php');

// Inicializar a variável de erro
$erroMensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCompleto = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING); 
    $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $confirmarSenha = filter_input(INPUT_POST, 'confirmar-senha', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);

    // Validação da senha
    if ($senha !== $confirmarSenha) {
        $erroMensagem = "Senha e confirmação não conferem.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validação do e-mail
        $erroMensagem = "E-mail inválido. Por favor, insira um endereço de e-mail válido.";
    } else {
        try {
            // Verificar se o nome de usuário já existe
            $sqlVerificaUsuario = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario";
            $stmtVerifica = $pdo->prepare($sqlVerificaUsuario);
            $stmtVerifica->execute([':usuario' => $usuario]);
            $usuarioExiste = $stmtVerifica->fetchColumn();

            if ($usuarioExiste > 0) {
                $erroMensagem = "O nome de usuário já está em uso. Escolha outro.";
            } else {
                // Criptografar a senha
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                // Inserir o novo usuário no banco
                $sql = "INSERT INTO usuarios (nome_completo, usuario, senha, e_mail, telefone) 
                        VALUES (:nomeCompleto, :usuario, :senha, :email, :telefone)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nomeCompleto' => $nomeCompleto, 
                    ':usuario' => $usuario,
                    ':senha' => $senhaHash, 
                    ':email' => $email, 
                    ':telefone' => $telefone
                ]);

                // Redireciona após cadastro bem-sucedido
                header("Location: /investimento/login.php?cadastro=sucesso");
                exit;
            }
        } catch (PDOException $e) {
            $erroMensagem = "Erro ao cadastrar usuário: " . $e->getMessage();
        }
    }
}
?>

<?php
$paginaAtual = "Cadastro";
$style = "assets/style.php";
$view = "url('assets/img/stocks.jpg')";
require("cabecalho.php");
?>
<body class="fundo">
    <?php
    $enderecoCadastro = "cadastro.php";
    $enderecos = [
        "login.php",
        "../inicial.php#sobre-nos"
    ];
    $listas = [
        "Acesse sua conta",
        "Sobre Nós"
    ];
    require("header.php");?>
    </header>
    <main>
        <form class="d-flex flex-column align-items-center tela-principal" method="post">
            <!-- Exibir mensagem de erro -->
            <?php if (!empty($erroMensagem)): ?>
                <p class="erro" style="color: red;"><?= $erroMensagem; ?></p>
            <?php endif; ?>

            <label for="nome"></label>
            <input type="text" name="nome" id="nome" placeholder="Nome Completo">

            <label for="usuario"></label>
            <input type="text" name="usuario" id="usuario" placeholder="Defina seu login">

            <label for="senha"></label>
            <input type="password" name="senha" id="senha" placeholder="Senha">

            <label for="senha"></label>
            <input type="password" name="confirmar-senha" id="confirmar-senha" placeholder="Confirmar Senha">

            <label for="email"></label>
            <input type="email" name="email" id="email" placeholder="E-mail">

            <label for="telefone"></label>
            <input type="tel" name="telefone" id="telefone" placeholder="Número de telefone">

            <button class="acesse" type="submit" name="cadastrar">Cadastrar</button>
            <!--Quando o usuário clicar, os dados devem ser salvos no banco de dados-->
        </form>
    </main>
    <?php
    require("../view/rodape.php");
    ?>
    </body>
</html>