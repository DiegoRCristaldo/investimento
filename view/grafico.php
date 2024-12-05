<?php
require("./data/coneccao.php");
require("./data/Carrossel.php");

$idUsuarioLogado = $_SESSION['login']->id;

$dadosToken = include('./data/dados.php');
$token = $dadosToken['token'];
$apiUrl = "https://brapi.dev/api/quote/";

function valorizacao($precoAtual, $precoMedio){
    if ($precoMedio == 0) return '0,00%';
    $valorizacao = (($precoAtual - $precoMedio) / $precoMedio) * 100;
    return number_format($valorizacao, 2, ',', '.') . '%';
}

$graficoBarras = [];

// Verifica se o ativo já está na carteira do usuário
$sqlConsultaCarteira = "SELECT ativo, id_ativo, preco_medio, quantidade 
FROM carteira c JOIN ativos a ON c.id_ativo = a.id 
WHERE id_usuario = :id_usuario;";
$stmtConsulta = $pdo->prepare($sqlConsultaCarteira);
$stmtConsulta->execute(['id_usuario' => $idUsuarioLogado]);
$carteira = $stmtConsulta->fetchAll(PDO::FETCH_ASSOC);

foreach ($carteira as $item){
    $nome = $item['ativo'];
    $precoMedio = $item['preco_medio'];
    $quantidade = $item['quantidade'];
    $valorTotal = $precoMedio * $quantidade;

    $url = "{$apiUrl}{$nome}?token={$token}";
    // Faz a requisição HTTP usando file_get_contents
    $response = file_get_contents($url);
    
    if ($response === false) {
        return ["error" => "Erro ao acessar a API"];
    }

    // Decodifica o JSON da resposta em um array associativo
    $data = json_decode($response, true);

    if (!isset($data['results'][0]['regularMarketPrice'])) {
        $valorAtual = 'N/A'; // Preço não encontrado
    } else {
        $valorAtual = $data['results'][0]['regularMarketPrice'];
    }

    $valorizacao = number_format((($valorAtual - $precoMedio) / $precoMedio) * 100, 2, ',', '.').'%';

    $graficoBarras[] = [
        'ativo' => $nome,
        'quantidade' => $quantidade,
        'precoAtual' => number_format($valorAtual, 2, ',', '.'),
        'precoMedio' => number_format($precoMedio, 2, ',', '.'),
        'valorizacao' => $valorizacao,
        'valorTotal' => $valorTotal
    ];
}

// O gráfico de pizza é igual ao gráfico de barras
$graficoPizza = $graficoBarras;

// Gerar gráficos no frontend
// Use funções do PHP para transformar dados em JSON que podem ser interpretados pelo JavaScript
$graficoBarrasJSON = json_encode($graficoBarras, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$graficoPizzaJSON = json_encode($graficoPizza, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
