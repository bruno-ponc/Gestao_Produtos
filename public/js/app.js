document.addEventListener("DOMContentLoaded", function() {
    if (document.getElementById("tabelaProdutosCesta")) {
        carregarProdutos(); 
    }
    if (document.getElementById("tabelaProdutosCRUD")) {
        carregarProdutosGerenciamento(); 
    }
    if (document.getElementById("tabelaFornecedoresPropria")) {
        carregarFornecedores(); 
    }

    document.addEventListener("input", function(e) {
        if (e.target && e.target.name === "cnpj") {
            e.target.value = e.target.value.replace(/[^0-9]/g, ""); 
        }
    });
});

function carregarProdutos() {
    const busca = document.getElementById("inputBusca") ? document.getElementById("inputBusca").value : "";
    fetch(`api/acoes.php?acao=listar_produtos&busca=${encodeURIComponent(busca)}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("tabelaProdutosCesta");
            if (!tbody) return;
            tbody.innerHTML = "";
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum produto disponível.</td></tr>`;
                return;
            }
            
            data.forEach(prod => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td><input type="checkbox" name="produtos_selecionados[]" value="${prod.id}" class="form-check-input"></td>
                    <td>${prod.nome}</td>
                    <td>R$ ${parseFloat(prod.preco).toFixed(2)}</td>
                    <td>${prod.fornecedor_nome || 'Não informado'}</td>
                `;
                tbody.appendChild(tr);
            });
        }).catch(err => console.error("Erro ao carregar produtos:", err));
}

function carregarProdutosGerenciamento() {
    const busca = document.getElementById("inputBusca") ? document.getElementById("inputBusca").value : "";
    fetch(`api/acoes.php?acao=listar_produtos&busca=${encodeURIComponent(busca)}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("tabelaProdutosCRUD");
            if (!tbody) return;
            tbody.innerHTML = "";
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center">Nenhum produto cadastrado.</td></tr>`;
                return;
            }
            
            data.forEach(prod => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${prod.id}</td>
                    <td>${prod.nome}</td>
                    <td>R$ ${parseFloat(prod.preco).toFixed(2)}</td>
                    <td>${prod.fornecedor_nome || 'Não informado'}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm me-1" onclick='abrirModalEditar(${JSON.stringify(prod)})'>Editar</button>
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Excluir este produto permanentemente?');">
                            <input type="hidden" name="excluir_produto" value="1">
                            <input type="hidden" name="id" value="${prod.id}">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }).catch(err => console.error("Erro ao gerenciar produtos:", err));
}

function carregarFornecedores() {
    fetch(`api/acoes.php?acao=listar_fornecedores`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("tabelaFornecedoresPropria");
            if (!tbody) return;
            tbody.innerHTML = "";
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center">Nenhum fornecedor cadastrado.</td></tr>`;
                return;
            }
            
            data.forEach(forn => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${forn.id}</td>
                    <td>${forn.nome}</td>
                    <td>${forn.cnpj}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm me-1" onclick='abrirModalEditarFornecedor(${JSON.stringify(forn)})'>Editar</button>
                        <form method="POST" style="display:inline-block;" onsubmit="return confirm('Excluir este fornecedor? Produtos vinculados a ele podem impedir a exclusão.');">
                            <input type="hidden" name="excluir_fornecedor" value="1">
                            <input type="hidden" name="id" value="${forn.id}">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }).catch(err => console.error("Erro ao carregar fornecedores:", err));
}

function abrirModalEditar(produto) {
    document.getElementById("edit_id").value = produto.id;
    document.getElementById("edit_nome").value = produto.nome;
    document.getElementById("edit_preco").value = produto.preco;
    document.getElementById("edit_fornecedor").value = produto.fornecedor_id;
    new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

function abrirModalEditarFornecedor(forn) {
    document.getElementById("edit_forn_id").value = forn.id;
    document.getElementById("edit_forn_nome").value = forn.nome;
    document.getElementById("edit_forn_cnpj").value = forn.cnpj;
    new bootstrap.Modal(document.getElementById('modalEditarFornecedor')).show();
}

document.addEventListener("submit", function(e) {
    const inputCNPJ = e.target.querySelector("input[name='cnpj']");
    if (inputCNPJ) {
        const cnpjValor = inputCNPJ.value.replace(/[^\d]+/g, '');
        if (cnpjValor.length !== 14) {
            e.preventDefault(); 
            alert("O CNPJ deve conter 14 números!"); 
            inputCNPJ.focus();
        }
    }
});