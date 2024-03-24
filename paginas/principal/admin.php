<?php
  require('../../visao/cabecalhoAdmin.php');
  require('../../visao/carrossel.php');
?>
        <nav class="menu">
            <div>
                <h2><a href="admin.php">Guia Financeiro</a></h2>
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
            </div>
        </nav>
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
          <form id="adicionarAtivo" class="coluna">
            <label for="ativo">Nome do ativo:</label>
            <input type="text" name="ativo" id="ativo">

            <label for="preco">Preço:</label>
            <input type="number" name="preco" id="preco">

            <label for="qtde">Quantidade:</label>
            <input type="number" name="quantidade" id="qtde">

            <label for="compra">Comprar</label>
            <input type="radio" name="ordem" id="compra" actived>
            
            <label for="venda">Vender</label>
            <input type="radio" name="ordem" id="venda">

            <button type="submit" value="Enviar Ordem">Enviar Ordem</button>
          </form>
        </div>
      </div>
    </main>
    <?php
      require('../../visao/rodape.php');