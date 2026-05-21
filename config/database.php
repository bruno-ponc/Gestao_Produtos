<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = ""; 
    private $db_name = "gestao_produtos";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS `$this->db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
            $this->conn->exec("USE `$this->db_name`;");
            
            $this->criarTabelas();

        } catch(PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }

    private function criarTabelas() {
        $sqlUsuarios = "CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            senha VARCHAR(64) NOT NULL
        );";
        
        $sqlFornecedores = "CREATE TABLE IF NOT EXISTS fornecedores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            cnpj VARCHAR(20) NOT NULL UNIQUE
        );";

        $sqlProdutos = "CREATE TABLE IF NOT EXISTS produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            preco DECIMAL(10,2) NOT NULL,
            fornecedor_id INT NOT NULL,
            FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE CASCADE
        );";

        $this->conn->exec($sqlUsuarios);
        $this->conn->exec($sqlFornecedores);
        $this->conn->exec($sqlProdutos);
    }
}
?>