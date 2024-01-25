const apiKey = "5EYYAKDH488FE1PT";
const symbol = "PETR4.SAO";

function getStockQuote(){
  const url = 'https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=${symbol}&apikey=${apiKey}';
  
  fetch(url)
    .then(response => response.json())
    .then(data => {
      const quote = data["Global Quote"]["05. price"];

      document.getElementById("stockQuote").innerText = "Cotação: R$ ${quote}";
    })
    .catch(error => {
      console.log("Ocorreu um erro ao obter a cotação da ação:", error);
    });
}

//Atualize as ações a cada 5 segundos
setInterval(getStockQuote, 5000);
