<?php
session_start();
if(!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit; }

require_once 'config/database.php';
require_once 'classes/Fornecedor.php';
require_once 'classes/Produto.php';
require_once 'classes/Cesta.php';

$database = new Database();
$db = $database->getConnection();

$fornecedorObj = new Fornecedor($db);
$produtoObj = new Produto($db);
$cestaObj = new Cesta();

$msg_erro = "";
$msg_sucesso = "";

if (isset($_GET['erro_cnpj'])) {
    $msg_erro = "Este CNPJ já está cadastrado para outro fornecedor!";
}
if (isset($_GET['sucesso_fornecedor'])) {
    $msg_sucesso = "Fornecedor cadastrado com sucesso!";
}
if (isset($_GET['sucesso_produto'])) {
    $msg_sucesso = "Produto cadastrado com sucesso!";
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['cadastrar_fornecedor'])) {
        try {
            $fornecedorObj->inserir($_POST['nome'], $_POST['cnpj']);
            header("Location: painel.php?sucesso_fornecedor=1"); 
            exit;
        } catch (Exception $e) {
            if ($e->getMessage() === "CNPJ_INVALIDO") {
                $msg_erro = "O CNPJ precisa ter 14 números.";
            } else if ($e->getCode() == 23000) {
                header("Location: painel.php?erro_cnpj=1");
                exit;
            } else {
                $msg_erro = "Erro no sistema: " . $e->getMessage();
            }
        }
    }
    

    if(isset($_POST['cadastrar_produto'])) {
        $produtoObj->inserir($_POST['nome'], $_POST['preco'], $_POST['fornecedor_id']);
        header("Location: painel.php?sucesso_produto=1"); 
        exit;
    }
    
    if(isset($_POST['add_cesta'])) {
        if(!empty($_POST['produtos_selecionados'])) {
            foreach($_POST['produtos_selecionados'] as $p_id) {
                $p_info = $produtoObj->buscarPorId($p_id);
                if($p_info) $cestaObj->adicionar($p_info);
            }
        }
        header("Location: cesta.php");
        exit;
    }
}

$fornecedores = $fornecedorObj->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Produtos - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <header class="navbar navbar-dark sticky-top p-3 shadow">
        <a href="painel.php" class="navbar-brand mb-0 h1 text-decoration-none text-white">Gestão de Produtos</a>
        <span class="text-white">Olá, <?=$_SESSION['usuario_nome']?> | <a href="logout.php" class="text-danger text-decoration-none fw-bold">Sair</a></span>
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
            <?php if(!empty($msg_erro)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Atenção:</strong> <?=$msg_erro?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(!empty($msg_sucesso)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sucesso!</strong> <?=$msg_sucesso?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card p-3 shadow-sm">
                        <h5>Cadastrar Fornecedor</h5>
                        <form method="POST">
                            <input type="hidden" name="cadastrar_fornecedor" value="1">
                            <div class="mb-2"><input type="text" name="nome" placeholder="Nome do Fornecedor" class="form-control" required></div>
                            <div class="mb-2">
                                <input type="tel" name="cnpj" placeholder="CNPJ" class="form-control" maxlength="14" inputmode="numeric" pattern="[0-9]{14}" required>
                            </div>
                            <button class="btn btn-custom-success btn-sm w-100">Salvar Fornecedor</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-3 shadow-sm">
                        <h5>Cadastrar Produto</h5>
                        <form method="POST">
                            <input type="hidden" name="cadastrar_produto" value="1">
                            <div class="mb-2"><input type="text" name="nome" placeholder="Nome do Produto" class="form-control" required></div>
                            <div class="mb-2"><input type="number" step="0.01" name="preco" placeholder="Preço" class="form-control" required></div>
                            <div class="mb-2">
                                <select name="fornecedor_id" class="form-control" required>
                                    <option value="">Selecione o Fornecedor</option>
                                    <?php foreach($fornecedores as $f): ?>
                                        <option value="<?=$f['id']?>"><?=$f['nome']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button class="btn btn-custom-success btn-sm w-100">Salvar Produto</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Seleção de Produtos para Cesta</h5>
                    <div class="d-flex">
                        <input type="text" id="inputBusca" class="form-control form-control-sm me-2" placeholder="Filtrar rápido...">
                        <button type="button" onclick="carregarProdutos()" class="btn btn-secondary btn-sm">Filtrar</button>
                    </div>
                </div>

                <form method="POST">
                    <input type="hidden" name="add_cesta" value="1">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Escolher</th>
                                    <th>Produto</th>
                                    <th>Preço</th>
                                    <th>Fornecedor</th>
                                </tr>
                            </thead>
                            <tbody id="tabelaProdutosCesta"></tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-custom-success btn-sm mt-2">Adicionar à Cesta</button>
                </form>
            </div>
        </main>
    </div>

    <footer>©2026 Sistemas de Informação | Desenvolvimento de Aplicações para WEB II</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/app.js"></script>
</body>
</html>