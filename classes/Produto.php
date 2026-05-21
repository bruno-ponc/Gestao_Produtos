<?php
class Produto {
    private $conn;
    private $table = "produtos";

    public function __construct($db) { $this->conn = $db; }

    public function inserir($nome, $preco, $fornecedor_id) {
        $query = "INSERT INTO " . $this->table . " (nome, preco, fornecedor_id) VALUES (:nome, :preco, :fornecedor_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":fornecedor_id", $fornecedor_id);
        return $stmt->execute();
    }

    public function listar($busca = "") {
        if (!empty($busca)) {
            $query = "SELECT p.*, f.nome as fornecedor_nome FROM " . $this->table . " p 
                      INNER JOIN proveedores f ON p.fornecedor_id = f.id 
                      WHERE p.nome LIKE :busca ORDER BY p.id DESC";
            $stmt = $this->conn->prepare($query);
            $termo = "%" . $busca . "%";
            $stmt->bindParam(":busca", $termo);
        } else {
            $query = "SELECT p.*, f.nome as fornecedor_nome FROM " . $this->table . " p 
                      INNER JOIN fornecedores f ON p.fornecedor_id = f.id ORDER BY p.id DESC";
            $stmt = $this->conn->prepare($query);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editar($id, $nome, $preco, $fornecedor_id) {
        $query = "UPDATE " . $this->table . " SET nome = :nome, preco = :preco, fornecedor_id = :fornecedor_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":fornecedor_id", $fornecedor_id);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>