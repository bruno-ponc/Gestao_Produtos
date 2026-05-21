<?php
session_start();
if(!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

require_once 'config/database.php';
require_once 'classes/Fornecedor.php';
require_once 'classes/Cesta.php';

$database = new Database();
$db = $database->getConnection();
$fornecedorObj = new Fornecedor($db);
$cestaObj = new Cesta();

$msg_erro = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['editar_fornecedor'])) {
        try {
            $fornecedorObj->editar($_POST['id'], $_POST['nome'], $_POST['cnpj']);
            header("Location: fornecedores.php");
            exit;
        } catch (Exception $e) {
            if ($e->getMessage() === "CNPJ_INVALIDO") {
                $msg_erro = "O CNPJ precisa ter exatamente 14 números.";
            } else if ($e->getCode() == 23000) {
                $msg_erro = "Erro: Este CNPJ já pertence a outro fornecedor registrado!";
            } else {
                $msg_erro = "Erro ao atualizar: " . $e->getMessage();
            }
        }
    }
    if(isset($_POST['excluir_fornecedor'])) {
        try {
            $fornecedorObj->excluir($_POST['id']);
            header("Location: fornecedores.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $msg_erro = "Não é possível excluir este fornecedor pois existem produtos associados a ele!";
            } else {
                $msg_erro = "Erro ao excluir: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fornecedores Cadastrados</title>
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
                <li class="nav-item mb-2"><a href="produtos.php" class="nav-link rounded text-white">📦 Produtos</a></li>
                <li class="nav-item mb-2"><a href="fornecedores.php" class="nav-link rounded active">🏢 Fornecedores</a></li>
                <li class="nav-item mb-2"><a href="cesta.php" class="nav-link rounded text-white">🛒 Ver Cesta</a></li>
            </ul>
        </nav>

        <main id="content" class="container-fluid">
            <?php if(!empty($msg_erro)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Atenção:</strong> <?=$msg_erro?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card p-3 shadow-sm">
                <h3 class="mb-3">Lista de Fornecedores</h3>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome Fantasia / Razão Social</th>
                                <th>CNPJ</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaFornecedoresPropria"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="modal fade" id="modalEditarFornecedor" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" class="modal-content">
          <div class="modal-header">
              <h5>Editar Fornecedor</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="editar_fornecedor" value="1">
            <input type="hidden" name="id" id="edit_forn_id">
            <div class="mb-3">
                <label class="form-label">Nome / Razão Social</label>
                <input type="text" name="nome" id="edit_forn_nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">CNPJ</label>
                <input type="tel" name="cnpj" id="edit_forn_cnpj" class="form-control" maxlength="14" inputmode="numeric" pattern="[0-9]{14}" required>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success btn-sm">Atualizar</button>
          </div>
        </form>
      </div>
    </div>

    <footer>©2026 Sistemas de Informação | Desenvolvimento de Aplicações para WEB II</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>