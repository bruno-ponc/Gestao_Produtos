<?php
class Fornecedor {
    private $conn;
    private $table_name = "fornecedores";

    public function __construct($db) {
        $this->conn = $db;
    }

    private function validarQuantidadeCnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);
        return (strlen($cnpj) === 14);
    }

    public function listar() {
        $query = "SELECT id, nome, cnpj FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inserir($nome, $cnpj) {
        if (!$this->validarQuantidadeCnpj($cnpj)) {
            throw new Exception("CNPJ_INVALIDO");
        }

        $query = "INSERT INTO " . $this->table_name . " (nome, cnpj) VALUES (:nome, :cnpj)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cnpj", $cnpj);
        return $stmt->execute();
    }

    public function editar($id, $nome, $cnpj) {
        if (!$this->validarQuantidadeCnpj($cnpj)) {
            throw new Exception("CNPJ_INVALIDO");
        }

        $query = "UPDATE " . $this->table_name . " SET nome = :nome, cnpj = :cnpj WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cnpj", $cnpj);
        return $stmt->execute();
    }

    public function excluir($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>