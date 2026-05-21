<?php
session_start();
if(!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

require_once 'config/database.php';
require_once 'classes/Produto.php';
require_once 'classes/Fornecedor.php';
require_once 'classes/Cesta.php';

$database = new Database();
$db = $database->getConnection();

$produtoObj = new Produto($db);
$fornecedorObj = new Fornecedor($db);
$cestaObj = new Cesta();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['editar_produto'])) {
        $produtoObj->editar($_POST['id'], $_POST['nome'], $_POST['preco'], $_POST['fornecedor_id']);
        header("Location: produtos.php");
        exit;
    }
    if(isset($_POST['excluir_produto'])) {
        $produtoObj->excluir($_POST['id']);
        header("Location: produtos.php");
        exit;
    }
}
$fornecedores = $fornecedorObj->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"><title>Produtos Cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="navbar navbar-dark sticky-top p-3 shadow">
        <a href="painel.php" class="navbar-brand mb-0 h1 text-white text-decoration-none">Gestão de Produtos</a>
    </header>

    <div class="wrapper">
        <nav id="sidebar" class="p-3">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="painel.php" class="nav-link rounded text-white">🏠 Início</a></li>
                <li class="nav-item mb-2"><a href="produtos.php" class="nav-link rounded active">📦 Produtos</a></li>
                <li class="nav-item mb-2"><a href="fornecedores.php" class="nav-link rounded text-white">🏢 Fornecedores</a></li>
                <li class="nav-item mb-2"><a href="cesta.php" class="nav-link rounded text-white">🛒 Cesta</a></li>
            </ul>
        </nav>

        <main id="content" class="container-fluid">
            <div class="card p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>Gerenciamento de Produtos</h3>
                    <div class="d-flex">
                        <input type="text" id="inputBusca" class="form-control form-control-sm me-2" placeholder="Pesquisar produto...">
                        <button type="button" onclick="carregarProdutosGerenciamento()" class="btn btn-secondary btn-sm">Pesquisar</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th><th>Produto</th><th>Preço</th><th>Fornecedor</th><th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaProdutosCRUD">
                            </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" class="modal-content">
          <div class="modal-header"><h5>Editar Produto</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body">
            <input type="hidden" name="editar_produto" value="1"><input type="hidden" name="id" id="edit_id">
            <div class="mb-2"><input type="text" name="nome" id="edit_nome" class="form-control" required></div>
            <div class="mb-2"><input type="number" step="0.01" name="preco" id="edit_preco" class="form-control" required></div>
            <div class="mb-2">
                <select name="fornecedor_id" id="edit_fornecedor" class="form-control" required>
                    <?php foreach($fornecedores as $f): ?>
                        <option value="<?=$f['id']?>"><?=$f['nome']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
          </div>
          <div class="modal-footer"><button type="submit" class="btn btn-success btn-sm">Atualizar</button></div>
        </form>
      </div>
    </div>

    <footer>©2026 Sistemas de Informação | Desenvolvimento de Aplicações para WEB II</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>