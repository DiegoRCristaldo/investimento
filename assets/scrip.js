/*
// Dados para os gráficos
let graficoBarras = [{
        ativo: 'BBAS3',
        quantidade: '100',
        precoAtual: '50,45',
        precoMedio: '42,35',
        valorizacao: '10%'
    },
    {
        ativo: 'PETR4',
        quantidade: '100',
        precoAtual: '50,45',
        precoMedio: '42,35',
        valorizacao: '10%'
    },
    {
        ativo: 'GGBR3',
        quantidade: '100',
        precoAtual: '50,45',
        precoMedio: '42,35',
        valorizacao: '10%'
    },
    {
        ativo: 'OFSA4',
        quantidade: '100',
        precoAtual: '50,45',
        precoMedio: '42,35',
        valorizacao: '10%'
    },
    {
        ativo: 'ETER3',
        quantidade: '100',
        precoAtual: '50,45',
        precoMedio: '42,35',
        valorizacao: '10%'
    }];
let graficoPizza = graficoBarras;

// Variáveis para os gráficos
let barChart, pieChart, tabela;

// Função para criar o gráfico de barras
function createBarChart() {
    let ctx = document.getElementById('barChart').getContext('2d');
    let labels = graficoBarras.map(item => item.ativo);
    let data = graficoBarras.map(item => item.precoAtual.replace(',', '.')); // Substitui vírgula por ponto para interpretar como número

    barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Data',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
}

// Função para criar o gráfico de pizza
function createPieChart() {
    let ctx = document.getElementById('pieChart').getContext('2d');
    let labels = graficoPizza.map(item => item.ativo);
    let data = graficoPizza.map(item => item.precoAtual.replace(',', '.')); // Precisa substituir a vírgula por ponto para interpretar como número

    pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
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
                y: [{
                    display: false
                }],
                x: [{
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
    if (barChart) {
        barChart.destroy();
        document.getElementById('barChart').classList.remove('active');
    }
    if (tabela) {
        tabela.classList.remove('active');
    }
    createPieChart();
    document.getElementById('pieChart').classList.add('active');
}

function mostrarTabela() {
    if (barChart) {
        barChart.destroy();
        document.getElementById('barChart').classList.remove('active');
    }
    if (pieChart) {
        pieChart.destroy();
        document.getElementById('pieChart').classList.remove('active');
    }
    if (tabela) {
        tabela.classList.remove('active');
    }

    let tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = ''; // Limpar tabela

    for (let i = 0; i < graficoBarras.length; i++) {
        let ativo = graficoBarras[i].ativo;
        let quantidade = graficoBarras[i].quantidade;
        let precoAtual = graficoBarras[i].precoAtual;
        let precoMedio = graficoBarras[i].precoMedio;
        let valorizacao = graficoBarras[i].valorizacao;
        
        adicionarAtivo(ativo, quantidade, precoAtual, precoMedio, valorizacao); // Adiciona ativo com dados de gráfico
    }

    tabela = document.getElementById('tabela');
    tabela.classList.add('active');
}

function adicionarAtivo(ativo, quantidade, precoAtual, precoMedio, valorizacao) {
    let tableBody = document.getElementById('tableBody');
    let newRow = tableBody.insertRow();

    let cell1 = newRow.insertCell(0);
    let cell2 = newRow.insertCell(1);
    let cell3 = newRow.insertCell(2);
    let cell4 = newRow.insertCell(3);
    let cell5 = newRow.insertCell(4);

    cell1.innerHTML = ativo;
    cell2.innerHTML = quantidade; // Adicionando a quantidade na tabela
    cell3.innerHTML = precoAtual;
    cell4.innerHTML = precoMedio;
    cell5.innerHTML = valorizacao;
}

function excluirAtivo(index) {
    let tableBody = document.getElementById('tableBody');
    tableBody.deleteRow(index);
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
            let indiceAtivo = graficoBarras.ativo.indexOf(ativo);
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
});*/