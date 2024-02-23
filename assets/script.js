// Dados para os gráficos
let barChartLabels = ['BBAS3', 'PETR4', 'GGBR3', 'OFSA4', 'ETER3'];
let barChartData = [12, 19, 3, 5, 2];
let pieChartLabels = ['BBAS3', 'PETR4', 'GGBR3', 'OFSA4', 'ETER3'];
let pieChartData = [30, 10, 20, 15, 25];

// Variáveis para os gráficos
let barChart, pieChart, tabela;

// Função para criar o gráfico de barras
function createBarChart() {
    var ctx = document.getElementById('barChart').getContext('2d');
    barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: barChartLabels,
            datasets: [{
                label: 'Data',
                data: barChartData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

// Função para criar o gráfico de pizza
function createPieChart() {
    var ctx = document.getElementById('pieChart').getContext('2d');
    pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pieChartLabels,
            datasets: [{
                data: pieChartData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
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
            scales: {
                yAxes: [{
                    display: false
                }],
                xAxes: [{
                    display: false
                }]
            }
        }
    });
}

// Função para mostrar o gráfico de barras e ocultar o gráfico de pizza
function showBarChart() {
    if (pieChart || tabela) {
        pieChart.destroy();
        document.getElementById('pieChart').classList.remove('active');
    }
    if (tabela) {
        tabela.classList.remove('active');
    }
    createBarChart();
    document.getElementById('barChart').classList.add('active');
}

// Função para mostrar o gráfico de pizza e ocultar o gráfico de barras
function showPieChart() {
    if (barChart || tabela) {
        barChart.destroy();
        document.getElementById('barChart').classList.remove('active');
    }
    if (tabela) {
        tabela.classList.remove('active');
    }
    createPieChart();
    document.getElementById('pieChart').classList.add('active');
}

function adicionarAtivo(ativo, precoAtual, precoMedio, valorizacao) {
    let tableBody = document.getElementById('tableBody');
    let newRow = tableBody.insertRow();

    let cell1 = newRow.insertCell(0);
    let cell2 = newRow.insertCell(1);
    let cell3 = newRow.insertCell(2);
    let cell4 = newRow.insertCell(3);

    cell1.innerHTML = ativo;
    cell2.innerHTML = precoAtual;
    cell3.innerHTML = precoMedio;
    cell4.innerHTML = valorizacao;
}

function excluirAtivo(index) {
    let tableBody = document.getElementById('tableBody');
    tableBody.deleteRow(index);
}

function mostrarTabela() {
    if (barChart || pieChart) {
        barChart.destroy();
        pieChart.destroy();
        document.getElementById('barChart').classList.remove('active');
        document.getElementById('pieChart').classList.remove('active');
    }
    let tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = ''; // Limpar tabela
    for (let i = 0; i < barChartLabels.length; i++) {
        adicionarAtivo(barChartLabels[i], barChartData[i], '', ''); // Adiciona ativo com dados de gráfico
    }
    tabela = document.getElementById('tabela');
    tabela.classList.add('active');
}

// Inicializar com o gráfico de barras visível
createBarChart();
document.getElementById('barChart').classList.add('active');

document.getElementById("adicionarAtivo").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que o formulário seja enviado

    // Obtenha os valores dos campos do formulário
    let ativo = document.getElementById("ativo").value;
    let preco = document.getElementById("preco").value;
    let ordem = document.querySelector('input[name="ordem"]:checked').value;

    // Verifique se todos os campos foram preenchidos
    if (ativo && preco && ordem) {
        // Adicione ou remova o ativo conforme a ordem
        if (ordem === "compra") {
            adicionarAtivo(ativo, preco, '', '');
        } else if (ordem === "venda") {
            // Determine o índice do ativo a ser removido, se existir
            let indiceAtivo = barChartLabels.indexOf(ativo);
            if (indiceAtivo !== -1) {
                excluirAtivo(indiceAtivo); // Adicione a função excluirAtivo definida anteriormente
            }
        }
        // Limpe os campos do formulário após adicionar ou remover o ativo
        document.getElementById("ativo").value = "";
        document.getElementById("preco").value = "";
        document.getElementById("compra").checked = false;
        document.getElementById("venda").checked = false;
    } else {
        // Exiba uma mensagem de erro se algum campo estiver faltando
        alert("Por favor, preencha todos os campos do formulário.");
    }
});