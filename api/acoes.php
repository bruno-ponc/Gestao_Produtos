<?php
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';
require_once '../classes/Produto.php';
require_once '../classes/Fornecedor.php';

$database = new Database();
$db = $database->getConnection();

$acao = $_GET['acao'] ?? '';

if ($acao === 'listar_produtos') {
    $produtoObj = new Produto($db);
    $busca = $_GET['busca'] ?? '';
    
    $produtos = $produtoObj->listar($busca);
    
    echo json_encode($produtos);
    exit;
}

if ($acao === 'listar_fornecedores') {
    $fornecedorObj = new Fornecedor($db);
    $fornecedores = $fornecedorObj->listar();
    
    echo json_encode($fornecedores);
    exit;
}

echo json_encode([]);
exit;