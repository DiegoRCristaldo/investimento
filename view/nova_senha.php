<?php 
$view = "url('assets/img/index.jpg')";
$paginaAtual = "Nova Senha";
$style = "assets/style.php";

if (!isset($_GET['id_usuario'])) {
    echo "Erro: ID do usuário não encontrado.";
    exit;
}

$id_usuario = filter_var($_GET['id_usuario'], FILTER_SANITIZE_NUMBER_INT);

require("cabecalho.php");
?>
    <body class="cadastro fundo">
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
            require("header.php");
        ?>
    </header>
    <main>
    <form class="tela-principal d-flex flex-column justify-content-center align-items-center" 
        method="POST" action="/investimento/controler/password_reset_complete.php">
        <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
        <label for="password">Digite sua nova senha:</label>
        <input type="password" id="password" name="password" required>
        <button class="acesse" type="submit">Enviar</button>
    </form>

    </main>
    <?php
        require("rodape.php");
    ?>
    </body>
</html>