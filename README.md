# Sistema de Gestão de Produtos e Fornecedores

Um sistema web completo, responsivo e seguro para o gerenciamento de produtos, fornecedores e montagem de cestas de compras. Desenvolvido com uma arquitetura limpa, integrando **PHP Estruturado/Orientado a Objetos (PDO)** no backend e **AJAX Dinâmico (Fetch API)** no frontend para proporcionar uma experiência fluida e sem recarregamentos desnecessários de página.

---

## Funcionalidades Principais

- **Painel Unificado (Dashboard):** Centraliza cadastros rápidos e a listagem interativa de itens.
- **Gestão de Fornecedores (CRUD):** Cadastro, listagem, edição e exclusão de parceiros comerciais.
- **Gestão de Produtos (CRUD):** Controle total de mercadorias com vinculação obrigatória (Relacionamento Chave Estrangeira) a um fornecedor.
- **Regras de Negócio para CNPJ:**
  - Tratamento estrito de tamanho (exatamente 14 dígitos).
  - Máscara em tempo real por JavaScript (remoção instantânea de letras e caracteres especiais).
  - Garantia de integridade com bloqueio de duplicidade direto no banco de dados.
- **Sistema de Cesta de Compras:** Seleção dinâmica de múltiplos produtos via checkbox com persistência em sessão.
- **Segurança Básica:** Controle de acesso por sessões PHP (`session_start`).

---

## Tecnologias Utilizadas

- **Backend:** PHP 8.x 
- **Frontend:** HTML5, CSS3 Customizado, JavaScript
- **Framework Visual:** Bootstrap 5.3 (Componentes fluidos, Tabelas e Modais)
- **Banco de Dados:** MySQL / MariaDB

---

## Estrutura do Projeto

```text
gestao_produtos/
├── api/
│   └── acoes.php             # API que processa e entrega JSON para o AJAX
├── classes/
│   ├── Cesta.php             # Regras da sessão da cesta de compras
│   ├── Fornecedor.php        # Métodos de banco para fornecedores
│   └── Produto.php           # Métodos de banco para produtos
├── config/
│   └── database.php          # Configuração e conexão com o banco de dados
├── public/
│   ├── css/
│   │   └── style.css         # Estilização da interface e sidebar
│   └── js/
│       └── app.js            # Motor JavaScript (AJAX, máscaras e modais)
├── painel.php                # Tela inicial e cadastros
├── produtos.php              # Gerenciamento de produtos
├── fornecedores.php          # Gerenciamento de fornecedores
├── cesta.php                 # Resumo e ações da cesta de compras
├── index.php                 # Tela de Login do sistema
└── logout.php                # Encerramento de sessão
```

---

## Instalação e Configuração

### 1. Pré-requisitos

- Servidor local instalado (XAMPP, WampServer ou Laragon).
- PHP 8.0 ou superior habilitado.
- Extensão PDO-MySQL ativa no PHP.

### 2. Clonar ou Baixar o Projeto

Mova a pasta do projeto para o diretório de servidores do seu ambiente local (ex: `C:/xampp/htdocs/gestao_produtos`).

### 3. Configurar o Banco de Dados

Crie um banco de dados MySQL chamado `gestao_produtos` (ou o nome de sua preferência) e execute o seguinte script SQL para criar as tabelas estruturadas:

```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(14) UNIQUE NOT NULL
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    fornecedor_id INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE RESTRICT
);
```

(Certifique-se de inserir ao menos um usuário na tabela `usuarios` para realizar o login inicial.)

### 4. Ajustar Conexão do Sistema

Abra o arquivo `config/database.php` e altere as credenciais de acordo com as configurações do seu MySQL local:

```php
private $host = "localhost";
private $db_name = "gestao_produtos";
private $username = "seu_usuario";
private $password = "sua_senha";
```

### 5. Executar o Sistema

Abra o navegador e acesse:
`http://localhost/gestao_produtos/index.php`

### 6. Diagrama Entidade Relacionamento (DER)

<img width="1672" height="941" alt="der_gestao_produtos" src="https://github.com/user-attachments/assets/456cf33e-e6e7-466b-baa6-27de88eeb3fa" />

### 7. Telas

<img width="1876" height="920" alt="1" src="https://github.com/user-attachments/assets/bf41da35-0612-40f8-b94f-ee137589ef07" />
<img width="1876" height="920" alt="2" src="https://github.com/user-attachments/assets/5d48d7be-19cf-44b9-ab4c-07baa4645b15" />
<img width="1875" height="920" alt="3" src="https://github.com/user-attachments/assets/6854b863-1c0d-4dd9-b8c0-e94ae1e26a09" />
<img width="1875" height="920" alt="4" src="https://github.com/user-attachments/assets/32bd95bf-c3ff-4e38-a6da-c56848678428" />
<img width="1874" height="922" alt="5" src="https://github.com/user-attachments/assets/b0a45aae-891d-4e8f-ae64-2d9f537f02e1" />

