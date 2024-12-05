<?php
    require("controler/autenticacao.php");
    require("./data/Carteira.php");
    require("view/grafico.php");

    $nomeDoUsuarioLogado = $_SESSION['login']->nome_completo;
    $partesDoNome = explode(' ', $nomeDoUsuarioLogado);
    $primeiroNome = $partesDoNome[0];
    $ultimaLetra = substr($primeiroNome, -1);

    $paginaAtual = "Principal";
    $style = "view/assets/indexCss.php";
    $view = "url('view/assets/img/selic.webp')";
    require("view/cabecalho.php");
?>
<body class="d-flex flex-column fundo text-center">
  <?php
  $enderecoCadastro = "view/cadastro.php";
  $enderecos = [
    "index.php",
    "controler/adicionarAtivos.php",
    "controler/deletarAtivos.php",
    "",
    "/investimento/view/login.php?logout=1"
  ];
  $listas = [
    "Minha Carteira",
    "Adicionar Ativos",
    "Excluir ativos",
    "Bem Vindo(a) $primeiroNome",
    "Sair"
  ];
  require("view/header.php");
  ?>
  </header>
    <main class="d-flex flex-column align-items-center m-2">
        <h1 class="text-white text-center">Gráficos</h1>
        <div class="chart-container d-flex align-items-center">
          <!-- Gráfico de barras -->
          <canvas id="barChart" class="hidden" width="400" height="400"></canvas>
          <!-- Gráfico de pizza -->
          <canvas id="pieChart" class="hidden" width="400" height="400"></canvas>

          <!-- Tabela de dados -->
          <div class="table-responsive">
          <table id="tabela" class="hidden m-3">
              <thead>
                  <tr>
                      <th class="tabela-fixa">Ativo</th>
                      <th>Qtd</th>
                      <th>R$ Atual</th>
                      <th>R$ Médio</th>
                      <th>Lucro</th>
                      <th>Total</th>
                  </tr>
              </thead>
              <tbody class="text-white" id="tableBody">
              </tbody>
          </table>
        </div>
        </div>
        <div class="d-flex flex-wrap justify-content-center align-items-center">
          <button class="acesse m-1" onclick="showBarChart()">Barras</button>
          <button class="acesse m-1" onclick="showPieChart()">Pizza</button>
          <button class="acesse m-1" onclick="mostrarTabela()">Tabela</button>
        </div>
    </main>
    <?php require("./view/rodape.php");?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php require("view/assets/script.php");?>
  </body>
</html>