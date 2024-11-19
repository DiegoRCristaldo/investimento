<!--
  Eu quero um site de controle de investimento
  Onde o usuário possa digitar suas compras e vendas de ações e consultar sua carteira de ações e 
  fundos imobiliários
  Também deve mostrar o preço médio e se a variação é positiva ou negativa
  Essa variação deve estar em porcentagem
-->
<?php
    if(isset($_GET["logout"])){
        session_start();
        session_destroy();
    }


   if(isset($_POST["entrar"])) {
        //buscar usuario no BD
        require('./data/coneccao.php');
        
        $usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':usuario', $usuario);
        $stm->execute();

        $login = $stm->fetchObject();

        //verificar se senha está correta
        if($login && password_verify($senha, $login->senha)){
            session_start();
            $_SESSION["login"] = $login;
            header("Location: /investimento/index.php");
        }
        else{
            header("Location: /investimento/login.php?erro=1");
        }

   }
    
?>
<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./assets/style.css">
    <title>Guia do Mercado Financeiro</title>
  </head>

  <body class="index">
    <header>
      
    </header>
    <main>
      
      <div class="conteudo-principal">
        <div class="texto-principal">
          <h1>Guia do Mercado Financeiro</h1>
          <h3>Tudo que você precisa para controlar seus investimentos</h3>
          <p>O Guia do Mercado Financeiro foi desenvolvido para facilitar a vida do investidor brasileiro. 
        Você pode gerenciar seus ativos e/ou fazer simulações de investimento da melhor maneira possível.</p>
        </div>
        <div class="login">
          <form method="post">
            <div class="coluna">
              <h2>Bem Vindo Investidor!</h2>
              <label for="usuario"></label>
              <input type="text" name="usuario" id="usuario" placeholder="Nome de usuário: ">

              <label for="senha"></label>
              <input type="password" name="senha" id="senha" placeholder="Senha: ">
            </div>
            <div class="row">
              <button name="entrar" type="submit">Acessar conta</button>
              <button type="button"><a href="assets/cadastro.php">Novo usuário</a></button>
            </div>
            <div>
              <a class="d-flex flex-collunm" href="assets/nova-senha.php">Esqueci minha senha</a>
              <span class="variacaoNegativa">
                  <?php
                  if(isset($_GET["erro"]))
                      echo "usuário ou senha inválidos"
                  ?>
              </span>
            </div>
          </form>
        </div>
      </div>
    </main>
    <footer>
        <cite>
          <a href="https://rcdesenvolvimentodigital.com.br">
            &copy;2024 Guia do Investidor. Todos os direitos reservados. 
            Site desenvolvido por R Cristaldo Desenvolvimento Digital.
          </a>
        </cite>
    </footer>
  </body>
</html>