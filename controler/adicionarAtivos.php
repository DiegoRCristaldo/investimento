<?php
    require("autenticacao.php");
    require("../data/Carteira.php");

    $nomeDoUsuarioLogado = $_SESSION['login']->nome_completo;
    $partesDoNome = explode(' ', $nomeDoUsuarioLogado);
    $primeiroNome = $partesDoNome[0];
    $ultimaLetra = substr($primeiroNome, -1);

    $paginaAtual = "Principal";
    $style = "../view/assets/indexCss.php";
    $view = "url('../view/assets/img/selic.webp')";
    require("../view/cabecalho.php");
?>
<body class="fundo">
  <?php
  $enderecoCadastro = "../view/cadastro.php";
  $enderecos = [
    "../index.php",
    "adicionarAtivos.php",
    "deletarAtivos.php",
    "../index.php",
    "/investimento/view/login.php?logout=1"
  ];
  $listas = [
    "Minha Carteira",
    "Adicionar Ativos",
    "Excluir ativos",
    "Bem Vindo(a) $primeiroNome",
    "Sair"
  ];
  require("../view/header.php");
  ?>
  </header>
    <main>
      <form method="get" class="tela-principal coluna">
        <!-- Campo para o nome do ativo -->
        <label for="ativo">Nome do Ativo:</label>
        <input type="text" name="ativo" id="ativo" placeholder="Ex.: PETR4" required>
        
        <!-- Campo para o preço -->
        <label for="preco">Preço:</label>
        <input type="number" name="preco" id="preco" placeholder="0.01" step="0.01" required>

        <!-- Tipo do ativo (usando select) -->
        <fieldset>
            <label for="tipo">Selecione o tipo do ativo:</label>
            <select class="acesse" name="tipo" id="tipo" required>
                <option value="acao">Ação</option>
                <option value="fii">FII</option>
                <option value="externo">Mercado Externo</option>
                <option value="fixa">Renda Fixa</option>
                <option value="lci">LCI</option>
                <option value="lca">LCA</option>
                <option value="bdr">BDR</option>
                <option value="cripto">Criptomoeda</option>
                <option value="indice">Índice</option>
                <option value="derivativo">Derivativo</option>
                <option value="cdi">CDI</option>
                <option value="cdb">CDB</option>
            </select>
        </fieldset>

        <!-- Campo para quantidade -->
        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" placeholder="Ex.: 100" required>

        <!-- Ordem de compra ou venda -->
        <fieldset>
            <legend>Tipo de Ordem</legend>
            <label for="compra">
                <input type="radio" name="ordem" id="compra" value="compra" checked>
                Comprar
            </label>
            <label for="venda">
                <input type="radio" name="ordem" id="venda" value="venda">
                Vender
            </label>
        </fieldset>

        <!-- Botão de envio -->
        <button class="acesse" name="enviar" type="submit" value="true">Enviar Ordem</button>
      </form>
    </main>
    <?php require("../view/rodape.php");?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php require("../view/assets/script.php");?>
  </body>
</html>