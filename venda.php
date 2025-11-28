<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

$sqlNum = "SELECT MAX(numeroDaVenda) AS maxVenda FROM vendas";
$resultadoNum = mysqli_query($conexao, $sqlNum);
$rowNum = mysqli_fetch_assoc($resultadoNum);
$numeroDaVenda = ($rowNum['maxVenda'] ?? 0) + 1;

// Busca produtos quando o formulário for enviado
$produtosEncontrados = [];
$textoBusca = '';

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $textoBusca = $_GET['busca'];

    // Versão simplificada (escapa a entrada) sem caractere especial
    $textoBuscaEscaped = mysqli_real_escape_string($conexao, $textoBusca);
    $sql = "SELECT p.codigo, p.nome, p.precoUnitarioDaVenda, p.quantEstoque, p.unidMedida, m.nome as marca 
            FROM produto p 
            INNER JOIN marca m ON p.idMarca = m.codigo 
            WHERE p.status = 1 
            AND (p.nome LIKE '%$textoBuscaEscaped%' OR p.codigo LIKE '%$textoBuscaEscaped%')
            LIMIT 10";
    $resultado = mysqli_query($conexao, $sql);

    while ($row = mysqli_fetch_assoc($resultado)) {
        $produtosEncontrados[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDV - Sistema de Vendas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/venda.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="pdv-container">
        <!-- Painel Esquerdo -->
        <div class="left-panel">
            <div class="panel-header">
                <h2>Sistema de Vendas</h2>
                <p class="venda-numero">Venda - Pedido #<?= $numeroDaVenda ?></p>
            </div>

            <div class="tabs">
                <button class="tab active">Produto</button>
                <button class="tab">Cliente</button>
                <button class="tab">Pagamento</button>
            </div>

            <div class="panel-content">
                <div class="form-group">
                    <label>Buscar Produto</label>

                    <!-- Formulário de busca -->
                    <form method="GET" action="">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="busca" placeholder="Digite o nome ou código do produto"
                                value="<?= $textoBusca ?>" autofocus>
                        </div>
                    </form>

                    <!-- Resultados da busca -->
                    <?php if (!empty($textoBusca)) { ?>
                        <div class="search-results">
                            <?php if (count($produtosEncontrados) > 0) { ?>
                                <?php foreach ($produtosEncontrados as $produto) { ?>
                                    <div class="result-item">
                                        <h4><?= $produto['nome'] ?></h4>
                                        <p class="p">
                                            Código: <?= $produto['codigo'] ?> |
                                            <?= $produto['marca'] ?> |
                                            Estoque: <?= $produto['quantEstoque'] ?>
                                        </p>
                                        <p class="result-price">
                                            R$ <?= number_format($produto['precoUnitarioDaVenda'], 2, ',', '.') ?>
                                        </p>

                                        <!-- Formulário com JavaScript -->
                                        <div class="quantidade-group">
                                            <label>Qtde:</label>
                                            <input type="text" id="qtd-<?= $produto['codigo'] ?>" value="1" min="1"
                                                max="<?= $produto['quantEstoque'] ?>" maxlength="5" data-mask="numerico" class="input-quantidade">
                                            <button type="button" class="btn-adicionar" data-codigo="<?= $produto['codigo'] ?>"
                                                data-nome="<?= $produto['nome'] ?>" data-marca="<?= $produto['marca'] ?>"
                                                data-unidmedida="<?= $produto['unidMedida'] ?>"
                                                data-preco="<?= $produto['precoUnitarioDaVenda'] ?>"
                                                data-estoque="<?= $produto['quantEstoque'] ?>">
                                                <i class="fas fa-plus"></i> Adicionar
                                            </button>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="no-results">
                                    Nenhum produto encontrado para "<?= $textoBusca ?>"
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="panel-actions">
                <button class="btn btn-secondary">
                    <i class="fas fa-trash"></i> Excluir
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-check"></i> Finalizar
                </button>
            </div>
        </div>

        <!-- Painel Central -->
        <div class="center-panel">
            <div class="main-header">
                <h1><i class="fas fa-shopping-cart"></i> Itens Venda</h1>
                <div class="header-actions">
                    <button class="btn-new-sale" onclick="novaVenda()">
                        <i class="fas fa-plus"></i> Nova Venda
                    </button>
                </div>
            </div>

            <div class="products-header">
                <div>Produto</div>
                <div style="text-align: left;">Unid. Medida</div>
                <div style="text-align: right;">Qtde.</div>
                <div style="text-align: right;">Preço</div>
                <div style="text-align: right;">Subtotal</div>
                <div style="text-align: right;">Ações</div>
            </div>

            <!-- Lista de produtos será preenchida via JavaScript -->
            <div class="products-list" id="products-list">
                <div class="empty-cart" id="empty-cart">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc;"></i>
                    <p style="color: #999; margin-top: 1rem;">Nenhum produto adicionado</p>
                </div>
            </div>

            <div class="total-section">
                <div class="total-label">Total da Venda</div>
                <div class="total-value" id="total-value">R$ 0,00</div>
            </div>
        </div>

    </div>

    <script src="assets/js/masks.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('limpar') === '1') {
            localStorage.removeItem('carrinho_venda_' + numeroVenda);
            console.log('Carrinho limpo após finalizar venda');
        }
        // Array para armazenar os itens da venda
        let itensVenda = [];
        const numeroVenda = <?= $numeroDaVenda ?>;

        // CARREGAR carrinho do localStorage ao iniciar
        function carregarCarrinho() {
            const carrinhoSalvo = localStorage.getItem('carrinho_venda_' + numeroVenda);
            if (carrinhoSalvo) {
                try {
                    itensVenda = JSON.parse(carrinhoSalvo);
                    console.log('Carrinho carregado:', itensVenda);
                } catch (e) {
                    console.error('Erro ao carregar carrinho:', e);
                    itensVenda = [];
                }
            }
        }

        // SALVAR carrinho no localStorage
        function salvarCarrinho() {
            localStorage.setItem('carrinho_venda_' + numeroVenda, JSON.stringify(itensVenda));
            console.log('Carrinho salvo');
        }

        // Event Delegation - escuta cliques nos botões
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-adicionar');

            if (btn) {
                console.log('Botão clicado!');

                const produto = {
                    codigo: btn.dataset.codigo,
                    nome: btn.dataset.nome,
                    marca: btn.dataset.marca,
                    unidMedida: btn.dataset.unidmedida,
                    precoUnitarioDaVenda: parseFloat(btn.dataset.preco),
                    quantEstoque: parseInt(btn.dataset.estoque)
                };

                console.log('Produto:', produto);
                adicionarProduto(produto, produto.codigo);
            }
        });

        // Função para adicionar produto
        function adicionarProduto(produto, codigoProduto) {
            console.log('=== ADICIONAR PRODUTO ===');

            const quantidadeInput = document.getElementById('qtd-' + codigoProduto);

            if (!quantidadeInput) {
                alert('Erro: Campo de quantidade não encontrado');
                return;
            }

            const quantidade = parseInt(quantidadeInput.value);

            if (isNaN(quantidade) || quantidade < 1) {
                alert('Quantidade deve ser no mínimo 1');
                return;
            }

            if (quantidade > produto.quantEstoque) {
                alert(`Estoque disponível: ${produto.quantEstoque}`);
                return;
            }

            const indexExistente = itensVenda.findIndex(item => item.codigo == produto.codigo);

            if (indexExistente !== -1) {
                const novaQuantidade = itensVenda[indexExistente].quantidade + quantidade;

                if (novaQuantidade > produto.quantEstoque) {
                    alert(`Estoque disponível: ${produto.quantEstoque}`);
                    return;
                }

                itensVenda[indexExistente].quantidade = novaQuantidade;
            } else {
                const novoProduto = {
                    codigo: produto.codigo,
                    nome: produto.nome,
                    marca: produto.marca,
                    unidMedida: produto.unidMedida,
                    precoUnitario: produto.precoUnitarioDaVenda,
                    quantidade: quantidade,
                    quantEstoque: produto.quantEstoque
                };

                itensVenda.push(novoProduto);
            }

            quantidadeInput.value = 1;

            // SALVA no localStorage
            salvarCarrinho();

            renderizarCarrinho();
            mostrarMensagem('Produto adicionado!', 'success');
        }

        // Função para renderizar o carrinho
        function renderizarCarrinho() {
            console.log('=== RENDERIZAR CARRINHO ===');
            console.log('Total de itens:', itensVenda.length);

            const productsList = document.getElementById('products-list');

            if (!productsList) {
                console.error('Element products-list não encontrado!');
                return;
            }

            if (itensVenda.length === 0) {
                productsList.innerHTML = `
            <div class="empty-cart" style="display: flex; flex-direction: column; align-items: center; padding: 3rem;">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc;"></i>
                <p style="color: #999; margin-top: 1rem;">Nenhum produto adicionado</p>
            </div>
        `;
                atualizarTotal();
                return;
            }

            productsList.innerHTML = '';

            itensVenda.forEach((item, index) => {
                const subtotal = item.precoUnitario * item.quantidade;

                const productItem = document.createElement('div');
                productItem.className = 'product-item';
                productItem.innerHTML = `
            <div class="product-info">
                <h4>${item.nome}</h4>
                <p>Código: ${item.codigo} | ${item.marca}</p>
            </div>
            <div class="unidMedida">${item.unidMedida}</div>
            <div class="quantity">
                <button onclick="alterarQuantidade(${index}, -1)" class="btn-qtd-mini">-</button>
                <span>${item.quantidade}</span>
                <button onclick="alterarQuantidade(${index}, 1)" class="btn-qtd-mini">+</button>
            </div>
            <div class="price">R$ ${item.precoUnitario.toFixed(2).replace('.', ',')}</div>
            <div class="price">R$ ${subtotal.toFixed(2).replace('.', ',')}</div>
            <div class="product-actions">
                <button class="action-btn" onclick="removerProduto(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

                productsList.appendChild(productItem);
            });

            atualizarTotal();
        }

        // Função para alterar quantidade
        function alterarQuantidade(index, incremento) {
            const item = itensVenda[index];
            const novaQuantidade = item.quantidade + incremento;

            if (novaQuantidade < 1) {
                if (confirm('Deseja remover este produto?')) {
                    removerProduto(index);
                }
                return;
            }

            if (novaQuantidade > item.quantEstoque) {
                alert(`Estoque disponível: ${item.quantEstoque}`);
                return;
            }

            itensVenda[index].quantidade = novaQuantidade;

            // SALVA no localStorage
            salvarCarrinho();

            renderizarCarrinho();
        }

        // Função para remover produto
        function removerProduto(index) {
            if (confirm('Deseja remover este produto?')) {
                itensVenda.splice(index, 1);

                // SALVA no localStorage
                salvarCarrinho();

                renderizarCarrinho();
                mostrarMensagem('Produto removido', 'info');
            }
        }

        // Função para atualizar total
        function atualizarTotal() {
            const total = itensVenda.reduce((acc, item) => {
                return acc + (item.precoUnitario * item.quantidade);
            }, 0);

            const totalElement = document.getElementById('total-value');
            if (totalElement) {
                totalElement.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
            }
        }

        // Função para limpar venda
        function limparVenda() {
            if (itensVenda.length === 0) {
                alert('Carrinho já está vazio');
                return;
            }

            if (confirm('Deseja limpar todos os itens?')) {
                itensVenda = [];

                // REMOVE do localStorage
                localStorage.removeItem('carrinho_venda_' + numeroVenda);

                renderizarCarrinho();
                mostrarMensagem('Carrinho limpo', 'info');
            }
        }

        // Função para nova venda
        function novaVenda() {
            if (itensVenda.length > 0) {
                if (!confirm('Deseja descartar a venda atual e começar uma nova?')) {
                    return;
                }
            }

            // LIMPA localStorage antes de recarregar
            localStorage.removeItem('carrinho_venda_' + numeroVenda);

            window.location.href = 'venda.php';
        }

        // Função para finalizar venda
        function finalizarVenda() {
            if (itensVenda.length === 0) {
                alert('Adicione produtos antes de finalizar a venda');
                return;
            }

            console.log('Finalizando venda:', itensVenda);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'finalizar_venda.php';

            const inputNumero = document.createElement('input');
            inputNumero.type = 'hidden';
            inputNumero.name = 'numeroVenda';
            inputNumero.value = numeroVenda;
            form.appendChild(inputNumero);

            const inputItens = document.createElement('input');
            inputItens.type = 'hidden';
            inputItens.name = 'itens';
            inputItens.value = JSON.stringify(itensVenda);
            form.appendChild(inputItens);

            document.body.appendChild(form);

            // LIMPA localStorage após enviar
            localStorage.removeItem('carrinho_venda_' + numeroVenda);

            form.submit();
        }

        // Função para mostrar mensagens
        function mostrarMensagem(texto, tipo) {
            const msg = document.createElement('div');
            msg.className = `alert alert-${tipo}`;
            msg.textContent = texto;
            msg.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        background: ${tipo === 'success' ? '#4caf50' : tipo === 'info' ? '#2196f3' : '#ff9800'};
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

            document.body.appendChild(msg);

            setTimeout(() => {
                msg.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => msg.remove(), 300);
            }, 2000);
        }

        // Toggle das abas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // INICIALIZA: Carrega carrinho e renderiza
        document.addEventListener('DOMContentLoaded', function () {
            console.log('=== PÁGINA CARREGADA ===');
            carregarCarrinho();
            renderizarCarrinho();
        });
    </script>
</body>

</html>