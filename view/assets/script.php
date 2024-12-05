<script>
    // Receber dados do PHP como JSON
    const graficoBarras = <?= $graficoBarrasJSON; ?>;
    const graficoPizza = <?= $graficoPizzaJSON; ?>;

    let barChart, pieChart, tabela;

    // Função para criar o gráfico de barras
    function createBarChart() {
        let ctx = document.getElementById('barChart').getContext('2d');
        let labels = graficoBarras.map(item => item.ativo);
        let data = graficoBarras.map(item => item.valorTotal);

        barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Valor Total (R$)',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.85)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Função para criar o gráfico de pizza
    function createPieChart() {
        let ctx = document.getElementById('pieChart').getContext('2d');
        let labels = graficoPizza.map(item => item.ativo);
        let data = graficoPizza.map(item => item.valorTotal); // Converte para número

        pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.85)',
                        'rgba(54, 162, 235, 0.85)',
                        'rgba(255, 206, 86, 0.85)',
                        'rgba(75, 192, 192, 0.85)',
                        'rgba(153, 102, 255, 0.85)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
            }
        });
    }

    // Função para mostrar o gráfico de barras
    function showBarChart() {
        if (pieChart) pieChart.destroy();
        document.getElementById('pieChart').classList.remove('active');
        if (tabela) tabela.classList.remove('active');

        createBarChart();
        document.getElementById('barChart').classList.add('active');
    }

    // Função para mostrar o gráfico de pizza
    function showPieChart() {
        if (barChart) barChart.destroy();
        document.getElementById('barChart').classList.remove('active');
        if (tabela) tabela.classList.remove('active');

        createPieChart();
        document.getElementById('pieChart').classList.add('active');
    }

    // Função para mostrar a tabela
    function mostrarTabela() {
        if (barChart) barChart.destroy();
        document.getElementById('barChart').classList.remove('active');
        if (pieChart) pieChart.destroy();
        document.getElementById('pieChart').classList.remove('active');

        let tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = ''; // Limpar tabela

        graficoBarras.forEach(item => {
        // Converte valorização para número
        let valorizacaoNumerica = parseFloat(item.valorizacao.replace(',', '.').replace('%', ''));

        // Define a classe com base na valorização
        let classVariacao = '';
        if (valorizacaoNumerica > 0) {
            classVariacao = 'variacaoPositiva';
        } else if (valorizacaoNumerica < 0) {
            classVariacao = 'variacaoNegativa';
        } else {
            classVariacao = 'variacaoNeutra';
        }

        // Cria a linha da tabela
        let row = `<tr>
            <td class="tabela-fixa">${item.ativo}</td>
            <td>${parseInt(item.quantidade)}</td>
            <td>${item.precoAtual}</td>
            <td>${item.precoMedio}</td>
            <td class="${classVariacao}">${item.valorizacao}</td>
            <td>${parseFloat(item.valorTotal).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</td>
        </tr>`;
        tableBody.innerHTML += row;
    });

    // Ativa a tabela
    tabela = document.getElementById('tabela');
    tabela.classList.add('active');

    }

    // Inicializar com o gráfico de barras visível
    showBarChart();
</script>
