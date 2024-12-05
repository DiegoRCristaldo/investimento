<?php 
$view = "url('assets/img/index.jpg')";
$paginaAtual = "Nova Senha";
$style = "assets/style.php";

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
            method="POST" action="/investimento/controler/password_reset_request.php">
            <label for="email">Digite o e-mail cadastrado:</label>
            <input type="email" id="email" name="email" required>
            <button class="acesse" type="submit">Enviar</button>
        </form>
    </main>
    <?php
        require("rodape.php");
    ?>
    </body>
</html>