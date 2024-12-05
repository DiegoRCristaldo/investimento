<?php
// DESENVOLVIDO POR DIEGO RODRIGUES CRISTALDO
// API CARTEIRA DE INVESTIMENTOS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json; charset=UTF-8");

require("./data/coneccao.php");

function getJsonInput() {
    return json_decode(file_get_contents('php://input'), true);
}

// Endpoint POST /carteira - Adicionar ou atualizar ativo na carteira
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/carteira') {
    $dados = getJsonInput();

    if (!isset($dados["id_usuario"], $dados["ativo"], $dados["quantidade"], $dados["preco"], $dados["ordem"])) {
        http_response_code(400);
        echo json_encode(["mensagem" => "Preencha todos os campos obrigatórios."]);
        exit;
    }

    $pdo = getConnection();
    try {
        $pdo->beginTransaction();

        // Valida se o ativo já existe
        $stmtVerifica = $pdo->prepare("SELECT id FROM ativos WHERE ativo = :ativo");
        $stmtVerifica->execute([':ativo' => $dados["ativo"]]);
        $ativoExistente = $stmtVerifica->fetch(PDO::FETCH_ASSOC);

        if ($ativoExistente) {
            $idAtivo = $ativoExistente['id'];
        } else {
            // Insere o ativo na tabela de ativos
            $stmtInserirAtivo = $pdo->prepare("INSERT INTO ativos (ativo, tipo) VALUES (:ativo, :tipo)");
            $stmtInserirAtivo->execute([
                ':ativo' => strtoupper($dados["ativo"]),
                ':tipo' => $dados["tipo"],
            ]);
            $idAtivo = $pdo->lastInsertId();
        }

        // Busca ativo na carteira
        $stmtBuscaCarteira = $pdo->prepare("SELECT quantidade, preco_medio FROM carteira WHERE id_usuario = :id_usuario AND id_ativo = :id_ativo");
        $stmtBuscaCarteira->execute([
            ':id_usuario' => $dados["id_usuario"],
            ':id_ativo' => $idAtivo,
        ]);
        $carteira = $stmtBuscaCarteira->fetch(PDO::FETCH_ASSOC);

        if ($carteira) {
            $quantidadeAtual = $carteira["quantidade"];
            $precoMedioAtual = $carteira["preco_medio"];

            if ($dados["ordem"] === "compra") {
                $novoInvestimento = ($precoMedioAtual * $quantidadeAtual) + ($dados["preco"] * $dados["quantidade"]);
                $novaQuantidade = $quantidadeAtual + $dados["quantidade"];
                $novoPrecoMedio = $novoInvestimento / $novaQuantidade;
            } elseif ($dados["ordem"] === "venda") {
                if ($dados["quantidade"] > $quantidadeAtual) {
                    throw new Exception("Quantidade insuficiente para realizar a venda.");
                }
                $novaQuantidade = $quantidadeAtual - $dados["quantidade"];
                $novoPrecoMedio = $precoMedioAtual;
            } else {
                throw new Exception("Ordem inválida.");
            }

            // Atualiza a carteira
            $stmtAtualizaCarteira = $pdo->prepare("UPDATE carteira SET quantidade = :quantidade, preco_medio = :preco_medio WHERE id_usuario = :id_usuario AND id_ativo = :id_ativo");
            $stmtAtualizaCarteira->execute([
                ':quantidade' => $novaQuantidade,
                ':preco_medio' => $novoPrecoMedio,
                ':id_usuario' => $dados["id_usuario"],
                ':id_ativo' => $idAtivo,
            ]);
        } else {
            if ($dados["ordem"] === "venda") {
                throw new Exception("Não é possível vender um ativo que não está na carteira.");
            }

            // Insere novo ativo na carteira
            $stmtInsereCarteira = $pdo->prepare("INSERT INTO carteira (id_usuario, id_ativo, quantidade, preco_medio) VALUES (:id_usuario, :id_ativo, :quantidade, :preco)");
            $stmtInsereCarteira->execute([
                ':id_usuario' => $dados["id_usuario"],
                ':id_ativo' => $idAtivo,
                ':quantidade' => $dados["quantidade"],
                ':preco' => $dados["preco"],
            ]);
        }

        $pdo->commit();
        echo json_encode(["mensagem" => "Carteira atualizada com sucesso"]);
    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(400);
        echo json_encode(["mensagem" => $e->getMessage()]);
    }
}

// Endpoint GET /carteira - Listar ativos da carteira
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] == '/carteira') {
    $idUsuario = $_GET["id_usuario"] ?? null;

    if (!$idUsuario) {
        http_response_code(400);
        echo json_encode(["mensagem" => "ID do usuário é obrigatório."]);
        exit;
    }

    $pdo = getConnection();
    $stmt = $pdo->prepare("
        SELECT c.id_ativo, a.ativo, c.quantidade, c.preco_medio 
        FROM carteira c 
        INNER JOIN ativos a ON c.id_ativo = a.id 
        WHERE c.id_usuario = :id_usuario
    ");
    $stmt->execute([':id_usuario' => $idUsuario]);
    $carteira = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($carteira);
}

// Endpoint DELETE /carteira/:id - Remover ativo da carteira
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && preg_match('/\/carteira\/(\d+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $idCarteira = (int)$matches[1];

    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM carteira WHERE id = :id");
    $stmt->execute([':id' => $idCarteira]);

    echo json_encode(["mensagem" => "Ativo removido da carteira."]);
}
?>