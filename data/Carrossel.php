<?php
$dadosToken = include('dados.php');
class Carrossel {

    private string $apiUrl = "https://brapi.dev/api/quote/";
    private string $token; // Armazene o token em uma variável privada

    private string $apiCurrencyUrl = "https://api.currencyapi.com/v3/";
    private string $tokenCurrency;

    public function __construct(array $dadosToken){
        $this->token = $dadosToken['token'];
        $this->tokenCurrency = $dadosToken['tokenCurrency'];
    }

    public function getTicker(): array {
        // Array para armazenar os dados dos tickers
        $tickers = [
            /*"IBOVESPA" => $this->fetchDataAcoes("^BVSP"),
            "IFIX" => $this->fetchDataAcoes("IFIX.SA"),
            "DÓLAR" => $this->fetchDataMoedas("USD"),
            "EURO" => $this->fetchDataMoedas("EUR"),
            "BITCOIN" => $this->fetchDataMoedas("BTC"),
            "PETR4" => $this->fetchDataAcoes("PETR4"),
            "VALE3" => $this->fetchDataAcoes("VALE3"),
            "BBAS3" => $this->fetchDataAcoes("BBAS3"),
            "ITUB4" => $this->fetchDataAcoes("ITUB4"),
            "GGBR4" => $this->fetchDataAcoes("GGBR4")*/           
        ];

        return $tickers;
    }

    private function fetchDataAcoes(string $endpoint): array {
        $url = "{$this->apiUrl}{$endpoint}?token={$this->token}";
    
        // Faz a requisição HTTP usando file_get_contents
        $response = file_get_contents($url);
    
        if ($response === false) {
            return ["error" => "Erro ao acessar a API"];
        }
    
        // Decodifica o JSON da resposta em um array associativo
        $data = json_decode($response, true);
    
        // Verifica se a decodificação foi bem-sucedida e retorna um array válido
        if (is_null($data)) {
            return ["error" => "Erro ao decodificar os dados JSON"];
        } 
    
        return $data;
    }

    private function fetchDataMoedas(string $currencyPair): array {
        $endpoint = 'latest';
        $url = "{$this->apiCurrencyUrl}{$endpoint}?apikey={$this->tokenCurrency}&base_currency={$currencyPair}&currencies=BRL";
    
        // Faz a requisição HTTP
        $response = file_get_contents($url);
    
        if ($response === false) {
            // Captura o erro
            $error = error_get_last();
            return ["error" => "Erro ao acessar a API de moedas: " . $error['message']];
        }
    
        // Decodifica o JSON da resposta
        $data = json_decode($response, true);
    
        // Verifica se a chave 'rate' está presente
        if (isset($data['data']['BRL']['value'])) {
            return ['rate' => $data['data']['BRL']['value']];
        }
    
        return ["error" => "Erro ao acessar os dados de câmbio"];
    }        

    private function fetchData(string $url): array {
        $response = @file_get_contents($url);

        if ($response === false) {
            return ["error" => "Erro ao acessar a API"];
        }

        $data = json_decode($response, true);

        if (is_null($data)) {
            return ["error" => "Erro ao decodificar os dados JSON"];
        }

        return $data;
    }

    public function getValorAtual($dados, $nome) {
        // Verifica se os dados vêm da API de Ações (usando a chave 'results')
        if (isset($dados['results']) && is_array($dados['results'])) {
            // Aqui verificamos se o preço da ação está disponível
            if (isset($dados['results'][0]['regularMarketPrice'])) {
                return number_format($dados['results'][0]['regularMarketPrice'], 2, ',', '.');
            } else {
                // Se o preço não estiver disponível, retornamos 'N/A'
                return 'Preço de ação não encontrado';
            }
        }
        
        // Verifica se os dados são da API de Moedas (usando a chave 'rate')
        if (isset($dados['rate'])) {
            return number_format($dados['rate'], 2, ',', '.');
        }
        
        // Se não encontrar dados válidos, retorna 'N/A'
        return 'N/A';
    }   
    
    private array $currencyMapping = [
        "DÓLAR" => "USD",
        "EURO" => "EUR",
        "BITCOIN" => "BTC"
    ];

    public function getVariacao(array $tickerData, string $tickerName): string {
        if (isset($tickerData['results'][0]['regularMarketChangePercent'])) {
            return $tickerData['results'][0]['regularMarketChangePercent'] ?? 'N/A';
        } elseif (isset($tickerData['rate'])){
            if(isset($this->currencyMapping[$tickerName])) {
            $currencyPair = $this->currencyMapping[$tickerName];
            
            // Obtém a data de um dia anterior no formato necessário pela API
            $dataAnterior = new DateTime();
            $dataAnterior->modify('-1 day');
            $dataAnteriorFormatada = $dataAnterior->format('Y-m-d');
            $endpoint = 'historical';

            // Construção da URL para o endpoint histórico
            $url = "{$this->apiCurrencyUrl}{$endpoint}?apikey={$this->tokenCurrency}&base_currency={$currencyPair}&currencies=BRL&date={$dataAnteriorFormatada}";

            // Faz a requisição à API
            $response = file_get_contents($url);

            if ($response === false) {
                $error = error_get_last();
                return 'Erro ao acessar dados históricos: ' . $error['message'];
            }

            $data = json_decode($response, true);

            if (isset($data['data']['BRL']['value'])) {
                $valorAnterior = $data['data']['BRL']['value'];
                $valorAtual = $this->fetchDataMoedas($currencyPair)['rate'];

                if ((float)$valorAtual && (Float)$valorAnterior) {
                    $variacao = (($valorAtual - $valorAnterior) / $valorAnterior) * 100;
                    return $variacao;
                }
            }
            return 'N/A';
        }
    }
    
        return 'N/A';
    }
    
}

// Uso da classe Carrossel
$carrossel = new Carrossel();
$tickers = $carrossel->getTicker();
?>
