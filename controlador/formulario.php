<?php
    require('../data/banco/coneccao.php');

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];

        if($senha != $confirmar_senha){
            echo "A senha e a confirmação de senha são estão corretas."
        }
        else{
            try {
                // Criar uma nova conexão PDO
                $conexao = new PDO("mysql:host=$servidor;port=$porta;dbname=$bd", $usuario, $senha);
        
                // Configurar PDO para lançar exceções em caso de erro
                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                // Agora você pode executar consultas usando esta conexão
                // Por exemplo:
                $stmt = $conexao->query('SELECT * FROM carteira_de_acoes');
                while ($row = $stmt->fetch()) {
                    // Processar cada linha do resultado
                }
        
            } catch (PDOException $e) {
                // Se houver algum erro na conexão, será capturado aqui
                echo 'Erro de conexão: ' . $e->getMessage();
            }
        
        }
    }
    
?>

<section>
    <form class="formulario" action="../data/banco.php" method="post">
        <label for="nome"></label>
        <input class="layout" type="text" name="nome" id="nome" placeholder="Nome Completo"/>

        <label for="usuario"></label>
        <input class="layout" type="text" name="usuario" id="usuario" placeholder="Defina seu login"/>

        <label for="senha"></label>
        <input class="layout" type="password" name="senha" id="senha" placeholder="Senha"/>

        <label for="senha"></label>
        <input class="layout" type="password" name="senha" id="confirmar_senha" placeholder="Confirmar Senha"/>

        <label for="email"></label>
        <input class="layout" type="email" name="email" id="email" placeholder="E-mail"/>

        <label for="telefone"></label>
        <input class="layout" type="tel" name="telefone" id="telefone" placeholder="Número de telefone"/>

        <button onClick="" class="layout" type="submit">Cadastrar</button>
        <!--Quando o usuário clicar, os dados devem ser salvos no banco de dados-->
    </form>
</section>