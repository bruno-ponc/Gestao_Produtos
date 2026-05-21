<?php
require_once 'config/database.php';
require_once 'classes/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);
$msg = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if($usuario->cadastrar($_POST['nome'], $_POST['email'], $_POST['senha'])) {
            header("Location: index.php?cadastro=sucesso");
            exit; 
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $msg = "<div class='alert alert-danger'>Este e-mail já está cadastrado! Tente outro ou <a href='index.php'>faça login</a>.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Erro no sistema: " . $e->getMessage() . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="justify-content-center align-items-center">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px; margin-top: 8%;">
        <h3 class="text-center mb-3">Cadastro de Usuário</h3>
        <?=$msg?>
        <form method="POST">
            <div class="mb-3"><label>Nome</label><input type="text" name="nome" class="form-control" required></div>
            <div class="mb-3"><label>E-mail</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label>Senha</label><input type="password" name="senha" class="form-control" required></div>
            <button type="submit" class="btn btn-custom-success w-100">Cadastrar</button>
        </form>
    </div>
</body>
</html>