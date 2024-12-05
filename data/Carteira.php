<?php
require('coneccao.php');

class Carteira {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo; // Recebe conexão com o banco
    }

    public function atualizarCarteira() {
        try {
            if (!isset($_GET['enviar'])) {
                return; // Apenas processa se o botão "Enviar" for acionado
            }
            
            $idUsuario = $_SESSION['login']->id;
            // Valida e sanitiza os dados da entrada
            $ativo = strtoupper(filter_input(INPUT_GET, 'ativo', FILTER_SANITIZE_STRING));
            $preco = filter_input(INPUT_GET, 'preco', FILTER_VALIDATE_FLOAT);
            $tipo = filter_input(INPUT_GET, 'tipo', FILTER_SANITIZE_STRING);
            $quantidade = filter_input(INPUT_GET, 'quantidade', FILTER_VALIDATE_FLOAT);
            $ordem = filter_input(INPUT_GET, 'ordem', FILTER_SANITIZE_STRING);
    
            // Verifica se todos os campos necessários foram preenchidos
            if (!$idUsuario || !$ativo || !$preco || !$quantidade || !$ordem) {
                throw new Exception("Todos os campos são obrigatórios e devem ser válidos.");
            }
    
            // Verifica se o ativo já existe na tabela 'ativos'
            $sqlVerificaAtivo = "SELECT id FROM ativos WHERE ativo = :ativo";
            $stmtVerifica = $this->pdo->prepare($sqlVerificaAtivo);
            $stmtVerifica->execute([':ativo' => $ativo]);
            $ativoExistente = $stmtVerifica->fetch(PDO::FETCH_ASSOC);
    
            if ($ativoExistente) {
                $idAtivo = $ativoExistente['id'];
            } else {
                // Insere o ativo na tabela 'ativos'
                $sqlInserirAtivo = "INSERT INTO ativos (ativo, tipo) VALUES (:ativo, :tipo)";
                $stmtInserir = $this->pdo->prepare($sqlInserirAtivo);
                $stmtInserir->execute([
                    ':ativo' => $ativo,
                    ':tipo' => $tipo
                ]);
                $idAtivo = $this->pdo->lastInsertId();
            }
    
            // Verifica se o ativo já está na carteira do usuário
            $sqlBuscaCarteira = "SELECT quantidade, preco_medio FROM carteira WHERE id_ativo = :id_ativo AND id_usuario = :id_usuario";
            $stmtBusca = $this->pdo->prepare($sqlBuscaCarteira);
            $stmtBusca->execute([
                ':id_ativo' => $idAtivo,
                ':id_usuario' => $idUsuario
            ]);
            $carteira = $stmtBusca->fetch(PDO::FETCH_ASSOC);
    
            if ($carteira) {
                // Atualiza os dados do ativo na carteira existente
                $quantidadeAtual = $carteira['quantidade'];
                $precoMedioAtual = $carteira['preco_medio'];
    
                if ($ordem === 'compra') {
                    $novoInvestimento = ($precoMedioAtual * $quantidadeAtual) + ($preco * $quantidade);
                    $novaQuantidade = $quantidadeAtual + $quantidade;
                    $novoPrecoMedio = $novoInvestimento / $novaQuantidade;
                } elseif ($ordem === 'venda') {
                    if ($quantidade > $quantidadeAtual) {
                        throw new Exception("Quantidade insuficiente para realizar a venda.");
                    }
                    $novaQuantidade = $quantidadeAtual - $quantidade;
                    $novoPrecoMedio = $precoMedioAtual; // Não altera o preço médio em vendas
                } else {
                    throw new Exception("Ordem inválida.");
                }
    
                // Atualiza no banco de dados
                $sqlAtualizaCarteira = "UPDATE carteira 
                                        SET quantidade = :quantidade, preco_medio = :preco_medio 
                                        WHERE id_ativo = :id_ativo AND id_usuario = :id_usuario";
                $stmtAtualiza = $this->pdo->prepare($sqlAtualizaCarteira);
                $stmtAtualiza->execute([
                    ':quantidade' => $novaQuantidade,
                    ':preco_medio' => $novoPrecoMedio,
                    ':id_ativo' => $idAtivo,
                    ':id_usuario' => $idUsuario
                ]);
            } else {
                // Insere um novo registro na carteira
                if ($ordem === 'venda') {
                    throw new Exception("Não é possível vender um ativo que não está na carteira.");
                }
    
                $sqlInserirCarteira = "INSERT INTO carteira (id_usuario, id_ativo, quantidade, preco_medio) 
                                       VALUES (:id_usuario, :id_ativo, :quantidade, :preco_medio)";
                $stmtCarteira = $this->pdo->prepare($sqlInserirCarteira);
                $stmtCarteira->execute([
                    ':id_usuario' => $idUsuario,
                    ':id_ativo' => $idAtivo,
                    ':quantidade' => $quantidade,
                    ':preco_medio' => $preco
                ]);
            }
    
            // Redireciona com sucesso
            header("Location: /investimento/index.php?operacao=sucesso");
            exit;
        } catch (Exception $e) {
            // Trata erros e exibe mensagens
            echo "Erro ao atualizar a carteira: " . $e->getMessage();
        }
    }    

    private function processarOrdem($idUsuario, $idAtivo, $preco, $quantidade, $ordem) {
        // Busca dados atuais
        $sqlBuscaCarteira = "SELECT quantidade, preco_medio FROM carteira WHERE id_ativo = :id_ativo AND id_usuario = :id_usuario";
        $stmtBusca = $this->pdo->prepare($sqlBuscaCarteira);
        $stmtBusca->execute([
            ':id_ativo' => $idAtivo,
            ':id_usuario' => $idUsuario
        ]);
        $carteira = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if (!$carteira && $ordem === 'compra') {
            // Se o ativo não existe na carteira do usuário, insira um novo registro
            $sqlInserirCarteira = "INSERT INTO carteira (id_usuario, id_ativo, quantidade, preco_medio) 
                                   VALUES (:id_usuario, :id_ativo, :quantidade, :preco_medio)";
            $stmtInserir = $this->pdo->prepare($sqlInserirCarteira);
            $stmtInserir->execute([
                ':id_usuario' => $idUsuario,
                ':id_ativo' => $idAtivo,
                ':quantidade' => $quantidade,
                ':preco_medio' => $preco
            ]);
            return;
        } elseif (!$carteira && $ordem === 'venda') {
            echo "Ativo não encontrado na carteira para venda.";
            return;
        }

        $quantidadeAtual = $carteira['quantidade'];
        $precoMedioAtual = $carteira['preco_medio'];

        // Atualiza conforme a ordem
        if ($ordem === 'compra') {
            $novoInvestimento = ($precoMedioAtual * $quantidadeAtual) + ($preco * $quantidade);
            $novaQuantidade = $quantidadeAtual + $quantidade;
            $novoPrecoMedio = $novoInvestimento / $novaQuantidade;
        } elseif ($ordem === 'venda') {
            if ($quantidade > $quantidadeAtual) {
                echo "Quantidade insuficiente para venda.";
                return;
            }
            $novaQuantidade = $quantidadeAtual - $quantidade;
            $novoPrecoMedio = $precoMedioAtual; // Não altera em vendas
        } else {
            echo "Ordem inválida.";
            return;
        }

        // Atualiza no banco
        $sqlAtualizaCarteira = "UPDATE carteira SET quantidade = :quantidade, preco_medio = :preco_medio 
                                WHERE id_ativo = :id_ativo AND id_usuario = :id_usuario";
        $stmtAtualiza = $this->pdo->prepare($sqlAtualizaCarteira);
        $stmtAtualiza->execute([
            ':quantidade' => $novaQuantidade,
            ':preco_medio' => $novoPrecoMedio,
            ':id_ativo' => $idAtivo,
            ':id_usuario' => $idUsuario
        ]);
    }
}

$carteira = new Carteira($pdo);
// Processar a ordem
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['enviar'])) {
    $carteira->atualizarCarteira();
}