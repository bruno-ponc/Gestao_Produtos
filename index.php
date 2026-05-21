<?php
session_start();
if(isset($_SESSION['usuario_id'])) {
    header("Location: painel.php");
    exit;
}
require_once 'config/database.php';
require_once 'classes/Usuario.php';

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$erro = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $logado = $usuario->login($email, $senha);
    if($logado) {
        $_SESSION['usuario_id'] = $logado['id'];
        $_SESSION['usuario_nome'] = $logado['nome'];
        header("Location: painel.php");
        exit;
    } else {
        $erro = "Credenciais inválidas!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Produtos - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="justify-content-center align-items-center">
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px; margin-top: 10%;">
        <h3 class="text-center mb-3">Gestão de Produtos</h3>
        <?php if($erro): ?> <div class="alert alert-danger"><?=$erro?></div> <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom-success w-100">Entrar</button>
        </form>
        <div class="text-center mt-3">
            <a href="cadastro_usuario.php" class="text-decoration-none text-secondary">Criar uma conta</a>
        </div>
    </div>
</body>
</html>