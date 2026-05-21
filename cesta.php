<?php
session_start();
if(!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

require_once 'classes/Cesta.php';
$cestaObj = new Cesta();

if(isset($_GET['remover'])) {
    $cestaObj->remover($_GET['remover']);
    header("Location: cesta.php");
    exit;
}

$itens = $cestaObj->obterItens();
$resumo = $cestaObj->obterResumo();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><title>Cesta de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="navbar navbar-dark sticky-top p-3 shadow">
        <span class="navbar-brand mb-0 h1">Gestão de Produtos</span>
    </header>

    <div class="wrapper">
        <nav id="sidebar" class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="painel.php" class="nav-link rounded active">🏠 Início</a></li>
                <li class="nav-item mb-2"><a href="produtos.php" class="nav-link rounded text-white">📦 Produtos</a></li>
                <li class="nav-item mb-2"><a href="fornecedores.php" class="nav-link rounded text-white">🏢 Fornecedores</a></li>
                <li class="nav-item mb-2">
                    <a href="cesta.php" class="nav-link rounded text-white d-flex justify-content-between align-items-center">
                        🛒 Cesta <span class="badge bg-danger"><?=count($cestaObj->obterItens())?></span>
                    </a>
                </li>
            </ul>
        </nav>

        <main id="content" class="container-fluid">
            <h3>Cesta de Compras</h3>
            <div class="row">
                <div class="col-md-8">
                    <div class="card p-3 shadow-sm">
                        <table class="table align-middle">
                            <thead>
                                <tr><th>Produto</th><th>Preço (1 un.)</th><th>Remover</th></tr>
                            </thead>
                            <tbody>
                                <?php if(empty($itens)): ?>
                                    <tr><td colspan="3" class="text-center">Sua cesta está vazia.</td></tr>
                                <?php else: foreach($itens as $item): ?>
                                    <tr>
                                        <td><?=$item['nome']?></td>
                                        <td>R$ <?=number_format($item['preco'], 2, ',', '.')?></td>
                                        <td><a href="cesta.php?remover=<?=$item['id']?>" class="btn btn-danger btn-sm">X</a></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 shadow-sm bg-light">
                        <h5>Resumo da Cesta</h5>
                        <hr>
                        <p><strong>Produtos Selecionados:</strong> <?=$resumo['quantidade']?></p>
                        <p><strong>Valor Total:</strong> R$ <?=number_format($resumo['total'], 2, ',', '.')?></p>
                        <a href="painel.php" class="btn btn-secondary btn-sm w-100">Continuar Comprando</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer>©2026 Sistemas de Informação | Desenvolvimento de Aplicações para WEB II</footer>
</body>
</html>