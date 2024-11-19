<?php
// API CARTEIRA DE AÇÕES
// DESENVOLVIDO POR DIEGO RODRIGUES CRISTALDO

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");
require("./data/coneccao.php");
require("./Ativo.php");

function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true);
}

// Endpoint POST /ativos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/ativos') {
    $dados = getJsonInput();

    if (!isset($dados["ticker"], $dados["quantidade"], $dados["preco"])) {
        http_response_code(400);
        echo json_encode(["mensagem" => "Preencha todos os campos obrigatórios."]);
        exit;
    }

    $pdo = getConnection();
    $stmt = $pdo->prepare("INSERT INTO ativos (ticker, quantidade, preco, tipo, imagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$dados["ticker"], $dados["quantidade"], $dados["preco"], $dados["tipo"] ?? 'Ação', $dados["imagem"] ?? 'fundo_vazio.jpg']);
    
    $id = $pdo->lastInsertId();
    $novoAtivo = array_merge($dados, ["id" => $id]);
    echo json_encode($novoAtivo);
}

// Endpoint GET /ativos
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/ativos') {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT * FROM ativos");
    $ativos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($ativos);
}

// Endpoint PUT /ativos/:id
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && preg_match('/\/ativos\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = (int)$matches[1];
    $dados = getJsonInput();

    $pdo = getConnection();
    $stmt = $pdo->prepare("UPDATE ativos SET ticker = ?, quantidade = ?, preco = ? WHERE id = ?");
    $stmt->execute([$dados["ticker"], $dados["quantidade"], $dados["preco"], $id]);

    echo json_encode(["mensagem" => "Ativo atualizado com sucesso"]);
}

// Endpoint DELETE /ativos/:id
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && preg_match('/\/ativos\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = (int)$matches[1];

    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM ativos WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["mensagem" => "Ativo excluído com sucesso"]);
}
?>
