<?php
require("../data/Carrossel.php");

    if(isset($_GET["logout"])){
        session_start();
        session_destroy();
    }


   if(isset($_POST["entrar"])) {
        //buscar usuario no BD
        require('../data/coneccao.php');
        
        $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':usuario', $usuario);
        $stm->execute();

        $login = $stm->fetchObject();

        //verificar se senha está correta
        if($login && password_verify($senha, $login->senha)){
            session_start();
            $_SESSION["login"] = $login;
            header("Location: /investimento/index.php");
        }
        else{
            header("Location: /investimento/view/login.php?erro=1");
        }

   }
    
  $paginaAtual = "Login";
  $view = "url('assets/img/index.jpg')";
  $style = "assets/style.php";
  require("cabecalho.php");
?>
  <body class="d-flex flex-column fundo text-center">
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
    <main class="">
        <form class="tela-principal d-flex flex-column justify-content-center align-items-center" method="post">
          <div>
            <h3 class="sub-titulo text-center">Bem Vindo <strong class="titulo">Investidor</strong>!</h3>              <label for="usuario"></label>
            <input type="text" name="usuario" id="usuario" placeholder="Nome de usuário: "required>

            <label for="senha"></label>
            <input type="password" name="senha" id="senha" placeholder="Senha: " required>
          </div>
          <div class="d-flex flex-wrap align-items-center p-1">
            <button class="nova-conta m-1" name="entrar" type="submit">Acessar conta</button>
            <a class="nova-conta m-1 text-white" href="cadastro.php">Novo usuário</a>
          </div>
          <div class="d-flex flex-column align-items-center">
            <a class="text-white" href="solicitar_codigo.php">Esqueci minha senha</a>
            <h2 class="variacaoNegativa">
                <?php
                if(isset($_GET["erro"]))
                    echo "Usuário ou Senha Inválidos"
                ?>
            </h2>
          </div>
        </form>
    </main>
    <?php require("rodape.php");?>
  </body>
</html>