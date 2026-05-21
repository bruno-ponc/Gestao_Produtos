<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct($db) { $this->conn = $db; }

    public function cadastrar($nome, $email, $senha) {
        $query = "INSERT INTO " . $this->table . " (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($query);
        
        $senhaHash = hash('sha256', $senha); 

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senhaHash);

        return $stmt->execute();
    }

    public function login($email, $senha) {
        $query = "SELECT id, nome, senha FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(hash('sha256', $senha) === $row['senha']) {
                return $row;
            }
        }
        return false;
    }
}
?>