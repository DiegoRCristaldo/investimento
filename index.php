<?php
require("autenticacao.php");
require("./data/Carrossel.php");
require("./data/Carteira.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/style.css">
    <title>Guia do Mercado Financeiro</title>
</head>
<body class="admin">
    <header>
        <div class="tag-list">
            <div class="inner">
            <?php foreach ($tickers as $nome => $dados): ?>
              <?php
                $variacao = $carrossel->getVariacao($dados, $nome);

                if($variacao > 0){
                  $classVariacao = 'variacaoPositiva';
                }
                elseif($variacao < 0){
                  $classVariacao = 'variacaoNegativa';
                }
                else{
                  $classVariacao = 'variacaoNeutra';
                }
                $variacaoFormatada = number_format($variacao, 2, ',', '.') . '%';
              ?>
              <div class='tag'><?=$nome?>
                  <div class='valor'><?=$carrossel->getValorAtual($dados, $nome)?></div>
                  <div class='variacao <?=$classVariacao?>'><?=$variacaoFormatada?></div>
              </div>
            <?php endforeach; ?>
            <div class="fade"></div>
          </div>
        <nav class="menu">
            <div>
                <h2><a href="index.php">Guia do Mercado Financeiro</a></h2>
            </div>
            <div>
                <ul class="indice">
                    <li class="lista"><a class="link" href="">Minha Carteira</a></li>
                    <li class="lista"><a class="link" href="">Ações BR</a></li>
                    <li class="lista"><a class="link" href="">Fundos Imobiliários</a></li>
                    <li class="lista"><a class="link" href="">Renda Fixa</a></li>
                    <li class="lista"><a class="link" href="">Ações Extrangeiras</a></li>
                    <li class="lista"><a class="link" href="">Criptomoedas</a></li>
                </ul>
                <ul>
                  <li>Bem Vindo <?=$idUsuario?></li>
                  <li><a href="/investimento/login.php?logout=1" class="btn">Sair</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
      <div class="row">
        <div class="coluna">
          <h1>Carteira</h1>
          <div class="chart-container">
            <!-- Gráfico de barras -->
            <canvas id="barChart" width="400" height="400"></canvas>
            <!-- Gráfico de pizza -->
            <canvas id="pieChart" width="400" height="400"></canvas>
            <table id="tabela">
              <thead>
                <tr class="row">
                  <th>Ativos</th>
                  <th>Quantidade</th>
                  <th>Preço Atual</th>
                  <th>Preço Médio</th>
                  <th>Valorização</th>
                </tr>
              </thead>
              <tbody id="tableBody">
              </tbody>
            </table>
          </div>
          <div class="row">
            <button onclick="showBarChart()">Gráfico de Barras</button>
            <button onclick="showPieChart()">Gráfico de Pizza</button>
            <button onclick="mostrarTabela()">Meus ativos</button>
          </div>
        </div>
        <div>
          <form method="get" class="coluna">
            <!-- Campo para o nome do ativo -->
            <label for="ativo">Nome do Ativo:</label>
            <input type="text" name="ativo" id="ativo" placeholder="Ex.: PETR4" required>
            
            <!-- Campo para o preço -->
            <label for="preco">Preço:</label>
            <input type="number" name="preco" id="preco" placeholder="0.01" step="0.01" required>

            <!-- Tipo do ativo (usando select) -->
            <fieldset>
                <legend>Tipo do Ativo</legend>
                <label for="tipo">Selecione o tipo:</label>
                <select name="tipo" id="tipo" required>
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
            <button name="enviar" type="submit" value="true">Enviar Ordem</button>
          </form>

        </div>
      </div>
    </main>
    <footer>
        <cite>
          <a href="https://rcdesenvolvimentodigital.com.br">
            &copy;2024 Guia do Investidor. Todos os direitos reservados. 
            Site desenvolvido por R Cristaldo Desenvolvimento Digital.
          </a>
        </cite>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./assets/scrip.js"></script>
</body>
</html>