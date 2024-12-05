<style>
/*Inicio do Conteúdo do principal*/
<?php require("principalCss.php");?>
a.link:hover{
    color: #3f05d0;
}

/* Estilo para o contêiner dos gráficos */
.chart-container {
    align-self: center;
    width: 90%;
}

/* Estilo para cada gráfico individual */
canvas {
    display: none;
}
canvas.active {
    display: block;
}
canvas#barChart, canvas#pieChart, .table-responsive {
    margin: 0 auto;
}
table {
    display: none;
    padding: 0.5rem;
    background-color: rgba(34, 5, 251, 0.4);
    border-color: rgba(54, 162, 235, 1);
    border-radius: 1rem;
    margin: 0.5rem auto;
    border-collapse: collapse;
    width: 90%;
}
/* Estilo para a tabela */
thead{
    background-color: rgba(34, 5, 251, 0.55);
    color: #fff;
    font-size: 1.1rem;
}
th, td {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
td{
    text-align: center;
}
tr:hover, tr:nth-child(even):hover{
    background-color: rgba(34, 5, 251, 0.5);
}
tr:nth-child(even) {
    background-color: rgba(221, 226, 228, 0.35);
}
.tabela-fixa{
    background-color: rgba(34, 5, 251, 0.65);
    position: sticky;
    left: 0; 
    z-index: 2;
}
.tabela-fixa:hover{
    position: absolute;
    background-color: rgba(34, 5, 251, 1);
}
.variacao, .valor{
    padding: 0.8rem;
}
.variacaoPositiva{
    color: #10e094;
}
.variacaoNegativa{
    color: #FF0000;
}
.variacaoNeutra{
    color: #FFFFFF;
}

/* Estilo para a tabela quando está ativa */
table.active {
    display: table;
}
.hidden, .nova-conta {
    display: none;
}
/*Fim do Conteúdo do principal*/

<?php require("footerCss.php");?>
@media (min-width: 575px) {
    .chart-container{
        width: fit-content;
    }
    canvas#barChart, canvas#pieChart{
        margin: 0.5rem;
        width: 420px !important;
        height: 420px !important;
    }
}
@media (min-width: 768px) {
    .acesse {
        margin: 0 1rem 0 1rem !important;
        padding: 0.35rem 2.5rem !important;
        border-radius: 50px;
    }
    .flex-wrap{
        flex-wrap: nowrap !important;
    }
}
@media (min-width: 1440px) {
    canvas#barChart, canvas#pieChart {
        margin: 2rem;
        width: 520px !important;
        height: 520px !important;
    }
}
</style>