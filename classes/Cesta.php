<?php
class Cesta {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(!isset($_SESSION['cesta'])) {
            $_SESSION['cesta'] = [];
        }
    }

    public function adicionar($produto) {
        $_SESSION['cesta'][$produto['id']] = $produto;
    }

    public function obterItens() {
        return $_SESSION['cesta'];
    }

    public function remover($id) {
        if(isset($_SESSION['cesta'][$id])) {
            unset($_SESSION['cesta'][$id]);
        }
    }

    public function limpar() {
        $_SESSION['cesta'] = [];
    }

    public function obterResumo() {
        $total = 0;
        $qtd = count($_SESSION['cesta']);
        foreach($_SESSION['cesta'] as $item) {
            $total += $item['preco'];
        }
        return ['total' => $total, 'quantidade' => $qtd];
    }
}
?>