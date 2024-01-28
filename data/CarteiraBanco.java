import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class CarteiraBanco {
    public static void main(String[] args) {
        // Informações de conexão
        String url = "jdbc:mysql://localhost:3306/carteira_investimento";
        String usuario = "root";
        String senha = "";

        // Objeto de conexão
        Connection conexao = null;

        try {
            // Carregar o driver JDBC do MySQL
            Class.forName("com.mysql.cj.jdbc.Driver");

            // Estabelecer a conexão
            conexao = DriverManager.getConnection(url, usuario, senha);

            // Realizar operações no banco de dados aqui...

        } 
        catch (ClassNotFoundException e) {
            e.printStackTrace();
        } 
        catch (SQLException e) {
            e.printStackTrace();
        } 
        finally {
            // Fechar a conexão quando não for mais necessária
            if (conexao != null) {
                try {
                    conexao.close();
                } 
                catch (SQLException e) {
                    e.printStackTrace();
                }
            }
        }
    }
}