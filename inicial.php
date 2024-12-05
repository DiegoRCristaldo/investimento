<?php
require("./data/Carrossel.php");

$paginaAtual = "Login";
$view = "url('view/assets/img/index.jpg')";
$style = "view/assets/style.php";
require("view/cabecalho.php");
?>
  <body class="d-flex flex-column fundo text-center">
    <?php
    $enderecoCadastro = "view/cadastro.php";
    $enderecos = [
      "view/login.php",
      "#sobre-nos"
    ];
    $listas = [
      "Acesse sua conta",
      "Sobre Nós"
    ];
    require("view/header.php");
    require("view/carrossel.php");
    ?>
    <main class="d-flex flex-wrap justify-content-center align-items-center">
      <div class="d-flex flex-column">
        <h3 class="sub-titulo">Controle seus <strong class="titulo">investimentos</strong> aqui</h3>
          <div class="d-flex flex-column align-items-center texto-principal">
            <p class="texto">O Guia do Mercado Financeiro foi desenvolvido para facilitar a vida do investidor 
              brasileiro. Você pode gerenciar seus ativos e/ou fazer simulações de investimento da melhor maneira 
              possível.</p>
            <a href="view/cadastro.php" class="acesse laranja">Abra sua conta</a>
          </div>
      </div>
      <div class="d-flex flex-column">
        <h3 id="sobre-nos" class="sub-titulo">Sobre <strong class="titulo">Nós</strong></h3>
        <div class="d-flex flex-column align-items-center texto-principal">
          <p class="texto">Somos uma empresa brasileira fundada por Diego Rodrigues Cristaldo em dezembro de 2024, 
            com o objetivo de ajudar os nossos clientes a contrar seus investimentos sem vinculação nenhuma com a 
            conta real, portanto não há risco real em nossas plataformas.</p>
          <a href="view/cadastro.php" class="acesse laranja">Abra sua conta</a>
        </div>
      </div>
    </main>
    <?php
    require("view/rodape.php");
    ?>
  </body>
</html>