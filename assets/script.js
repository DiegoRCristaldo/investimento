// Dados para os gráficos
let barChartLabels = ['A', 'B', 'C', 'D', 'E'];
let barChartData = [12, 19, 3, 5, 2];
let pieChartLabels = ['A', 'B', 'C', 'D', 'E'];
let pieChartData = [30, 10, 20, 15, 25];

// Variáveis para os gráficos
let barChart, pieChart;

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
    if (pieChart) {
        pieChart.destroy();
    }
    createBarChart();
    document.getElementById('barChart').classList.add('active');
    document.getElementById('pieChart').classList.remove('active');
}

// Função para mostrar o gráfico de pizza e ocultar o gráfico de barras
function showPieChart() {
    if (barChart) {
        barChart.destroy();
    }
    createPieChart();
    document.getElementById('barChart').classList.remove('active');
    document.getElementById('pieChart').classList.add('active');
}

// Inicializar com o gráfico de barras visível
createBarChart();
document.getElementById('barChart').classList.add('active');
