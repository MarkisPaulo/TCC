<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

$aba_ativa = $_GET['aba'] ?? 'produto';

$sqlNum = "SELECT MAX(numeroDaVenda) AS maxVenda FROM vendas";
$resultadoNum = mysqli_query($conexao, $sqlNum);
$rowNum = mysqli_fetch_assoc($resultadoNum);
$numeroDaVenda = ($rowNum['maxVenda'] ?? 0) + 1;

$produtosEncontrados = [];
$textoBuscaProd = '';

$clientesEncontrados = [];
$textoBuscaCli = '';

if (isset($_GET['buscaProd']) && !empty($_GET['buscaProd'])) {
    $textoBuscaProd = $_GET['buscaProd'];

    // Versão simplificada (escapa a entrada) sem caractere especial
    $textoBuscaEscaped = mysqli_real_escape_string($conexao, $textoBuscaProd);
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

if (isset($_GET['buscaCli']) && !empty($_GET['buscaCli'])) {
    $textoBuscaCli = $_GET['buscaCli'];

    // Versão simplificada (escapa a entrada) sem caractere especial
    $textoBuscaEscaped = mysqli_real_escape_string($conexao, $textoBuscaCli);
    $sql = "SELECT c.codigo, c.nome, c.cpf_cnpj 
            FROM cliente c 
            WHERE c.status = 1 
            AND (c.nome LIKE '%$textoBuscaEscaped%' OR c.codigo LIKE '%$textoBuscaEscaped%')
            LIMIT 10";
    $resultado = mysqli_query($conexao, $sql);

    while ($row = mysqli_fetch_assoc($resultado)) {
        $clientesEncontrados[] = $row;
    }
}

// Busca clientes para o select
$sqlClientes = "SELECT codigo, nome, cpf_cnpj FROM cliente WHERE status = 1 ORDER BY nome";
$resultClientes = mysqli_query($conexao, $sqlClientes);
$clientes = [];
while ($row = mysqli_fetch_assoc($resultClientes)) {
    $clientes[] = $row;
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

            <!-- ABAS -->
            <div class="tabs">
                <button class="tab <?= $aba_ativa === 'produto' ? 'active' : '' ?>" data-tab="produto">
                    <i class="fas fa-box"></i> Produto
                </button>
                <button class="tab <?= $aba_ativa === 'cliente' ? 'active' : '' ?>" data-tab="cliente">
                    <i class="fas fa-user"></i> Cliente
                </button>
            </div>

            <!-- CONTEÚDO DAS ABAS -->
            <div class="panel-content">

                <!-- ABA PRODUTO -->
                <div class="tab-content <?= $aba_ativa === 'produto' ? 'active' : '' ?>" 
                style="<?= $aba_ativa === 'produto' ? '' : 'display : none;' ?>" id="tab-produto">
                    <div class="form-group">
                        <label>Buscar Produto</label>
                        <form method="GET" action="">
                            <input type="hidden" name="aba" value="produto">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" name="buscaProd" placeholder="Digite o nome ou código do produto"
                                    value="<?= $textoBuscaProd ?>">
                            </div>
                        </form> 

                        <?php if (!empty($textoBuscaProd)) { ?>
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

                                            <div class="quantidade-group">
                                                <label>Qtde:</label>
                                                <input type="text" id="qtd-<?= $produto['codigo'] ?>" data-mask="numerico"
                                                    maxlength="5" value="1" min="1" max="<?= $produto['quantEstoque'] ?>"
                                                    class="input-quantidade">
                                                <button type="button" class="btn-adicionar" 
                                                    data-codigo="<?= $produto['codigo'] ?>"
                                                    data-nome="<?= $produto['nome'] ?>" 
                                                    data-marca="<?= $produto['marca'] ?>"
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
                                        Nenhum produto encontrado para "<?= $textoBuscaProd ?>"
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- ABA CLIENTE -->
                <div class="tab-content <?= $aba_ativa === 'cliente' ? 'active' : '' ?>" 
                style="<?= $aba_ativa === 'cliente' ? '' : 'display : none;' ?>" id="tab-cliente">
                    <div class="form-group">
                        <label for="select-cliente">
                            <i class="fas fa-user"></i> Buscar Cliente
                        </label>
                        <form method="GET" action="" >
                            <input type="hidden" name="aba" value="cliente">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" name="buscaCli" placeholder="Digite o nome ou código do Cliente"
                                    value="<?= $textoBuscaCli ?>" autofocus>
                            </div>
                        </form>
                        <?php if (!empty($textoBuscaCli)) { ?>
                            <div class="search-results">
                                <?php if (count($clientesEncontrados) > 0) { ?>
                                    <?php foreach ($clientesEncontrados as $cliente) { ?>
                                        <div class="result-item" id="clienteVenda" onclick="selecionarCliente(<?= $cliente['codigo'] ?>, '<?= $cliente['nome'] ?>', '<?= $cliente['cpf_cnpj'] ?>')">
                                            <h4><?= $cliente['nome'] ?></h4>
                                            <p class="p">
                                                Código: <?= $cliente['codigo'] ?> |
                                                CPF/CNPJ: <?= $cliente['cpf_cnpj'] ?>   
                                            </p>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="no-results">
                                        Nenhum cliente encontrado para "<?= $textoBuscaCli ?>"
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <input type="hidden" id="clienteVenda" name="idCliente">
                    </div>

                    <div id="cliente-info" class="info-box" style="display: none;">
                        <h4><i class="fas fa-check-circle"></i> Cliente Selecionado</h4>
                        <p><strong>Nome:</strong> <span id="cliente-nome-display"></span></p>
                        <p><strong>CPF/CNPJ:</strong> <span id="cliente-cpf-display"></span></p>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" onclick="abrirCadastroCliente()">
                            <i class="fas fa-user-plus"></i> Cadastrar Novo Cliente
                        </button>
                    </div>

                    <div class="alert-info">
                        <i class="fas fa-info-circle"></i>
                        <p>A seleção do cliente é obrigatória para finalizar a venda.</p>
                    </div>
                </div>

            </div>

            <div class="panel-actions">
                <button class="btn btn-secondary" onclick="limparVenda()">
                    <i class="fas fa-trash"></i> Limpar
                </button>
                <button class="btn btn-primary" onclick="finalizarVenda()">
                    <i class="fas fa-check"></i> Resumo da Venda
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

        // Variáveis globais
        let itensVenda = [];
        const numeroVenda = <?= $numeroDaVenda ?>;

        // ========== ABAS ==========
        document.querySelectorAll('.tab').forEach((tab) => {
            tab.addEventListener('click', function () {
                // Remove active de todas as tabs
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                // Adiciona active na tab clicada
                this.classList.add('active');

                const tabId = this.dataset.tab;

                // Esconde todo o conteúdo das tabs
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                    content.classList.remove('active');
                });

                // Mostra apenas o conteúdo da tab ativa
                const activeContent = document.getElementById('tab-' + tabId);
                if (activeContent) {
                    activeContent.style.display = 'block';
                    activeContent.classList.add('active');
                }
            });
        });

        // ========== CARRINHO ==========
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

        function salvarCarrinho() {
            localStorage.setItem('carrinho_venda_' + numeroVenda, JSON.stringify(itensVenda));
            console.log('Carrinho salvo');
        }

        // ========== PRODUTOS ==========
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-adicionar');
            if (btn) {
                const produto = {
                    codigo: btn.dataset.codigo,
                    nome: btn.dataset.nome,
                    marca: btn.dataset.marca,
                    unidMedida: btn.dataset.unidmedida,
                    precoUnitarioDaVenda: parseFloat(btn.dataset.preco),
                    quantEstoque: parseInt(btn.dataset.estoque)
                };
                adicionarProduto(produto, produto.codigo);
            }
        });

        function adicionarProduto(produto, codigoProduto) {
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
                itensVenda.push({
                    codigo: produto.codigo,
                    nome: produto.nome,
                    marca: produto.marca,
                    unidMedida: produto.unidMedida,
                    precoUnitario: produto.precoUnitarioDaVenda,
                    quantidade: quantidade,
                    quantEstoque: produto.quantEstoque
                });
            }

            quantidadeInput.value = 1;
            salvarCarrinho();
            renderizarCarrinho();
            mostrarMensagem('Produto adicionado!', 'success');
        }

        function renderizarCarrinho() {
            const productsList = document.getElementById('products-list');
            if (!productsList) return;

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
            salvarCarrinho();
            renderizarCarrinho();
        }

        function removerProduto(index) {
            if (confirm('Deseja remover este produto?')) {
                itensVenda.splice(index, 1);
                salvarCarrinho();
                renderizarCarrinho();
                mostrarMensagem('Produto removido', 'info');
            }
        }

        function atualizarTotal() {
            const total = itensVenda.reduce((acc, item) => {
                return acc + (item.precoUnitario * item.quantidade);
            }, 0);
            const totalElement = document.getElementById('total-value');
            if (totalElement) {
                totalElement.textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
            }
        }

        // ========== VENDAS ==========
        function limparVenda() {
            if (itensVenda.length === 0) {
                alert('Carrinho já está vazio');
                return;
            }
            if (confirm('Deseja limpar todos os itens?')) {
                itensVenda = [];
                localStorage.removeItem('carrinho_venda_' + numeroVenda);
                renderizarCarrinho();
                mostrarMensagem('Carrinho limpo', 'info');
            }
        }

        function novaVenda() {
            if (itensVenda.length > 0) {
                if (!confirm('Deseja descartar a venda atual e começar uma nova?')) {
                    return;
                }
            }
            localStorage.removeItem('carrinho_venda_' + numeroVenda);
            window.location.href = 'venda.php';
        }

        function selecionarCliente(codigo, nome, cpf) {
            // Salva o cliente selecionado
            document.getElementById('clienteVenda').value = codigo;
            
            // Preenche os dados na div de confirmação
            document.getElementById('cliente-nome-display').textContent = nome;
            document.getElementById('cliente-cpf-display').textContent = cpf;
            
            // Mostra a div de cliente selecionado
            document.getElementById('cliente-info').style.display = 'block';
            
            mostrarMensagem(`Cliente "${nome}" selecionado!`, 'success');
        }

        function finalizarVenda() {
            if (itensVenda.length === 0) {
                alert('Adicione produtos antes de finalizar a venda');
                return;
            }

            const modalHTML = `
            <div id="modalFinalizar" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%;">
                    <h2 style="margin-bottom: 20px;">Finalizar Venda</h2>
                    <form id="formFinalizar" method="POST" action="finalizar_venda.php">
                        <input type="hidden" name="itens" value='${JSON.stringify(itensVenda)}'>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Cliente*</label>
                            <select name="idCliente" id="selectCliente" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Selecione o cliente</option>
                                <?php
                                $sqlClientes = "SELECT codigo, nome FROM cliente WHERE status = 1 ORDER BY nome";
                                $resultClientes = mysqli_query($conexao, $sqlClientes);
                                while ($cliente = mysqli_fetch_assoc($resultClientes)) {
                                    echo "<option value='{$cliente['codigo']}'>{$cliente['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Forma de Pagamento*</label>
                            <select name="formaRecebimento" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Selecione</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Cartão de Crédito">Cartão de Crédito</option>
                                <option value="Cartão de Débito">Cartão de Débito</option>
                                <option value="PIX">PIX</option>
                                <option value="Boleto">Boleto</option>
                                <option value="A Prazo">A Prazo</option>
                            </select>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Valor Total</label>
                            <input type="text" value="R$ ${calcularTotal().toFixed(2).replace('.', ',')}" disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #f5f5f5;">
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Valor Pago*</label>
                            <input type="text" name="valorPago" id="valorPago" data-mask="valor" placeholder="R$ 0,00" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                            <small style="color: #666;">Se pago parcial, o restante ficará em aberto</small>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Observações</label>
                            <textarea name="observacoes" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="fecharModal()" style="flex: 1; padding: 12px; background: #6c757d; color: white; border: none; border-radius: 6px; cursor: pointer;">Cancelar</button>
                            <button type="submit" style="flex: 1; padding: 12px; background: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer;">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

            document.body.insertAdjacentHTML('beforeend', modalHTML);

            // Pré-seleciona cliente se houver
            const clienteSelecionado = document.getElementById('clienteVenda')?.value;
            if (clienteSelecionado) {
                setTimeout(() => {
                    const selectModal = document.getElementById('selectCliente');
                    if (selectModal) selectModal.value = clienteSelecionado;
                }, 50);
            }

            // Inicializa máscara
            setTimeout(() => {
                if (window.MaskUtils) {
                    window.MaskUtils.initializeMasks();
                }
            }, 100);
        }

        function calcularTotal() {
            return itensVenda.reduce((acc, item) => acc + (item.precoUnitario * item.quantidade), 0);
        }

        function fecharModal() {
            const modal = document.getElementById('modalFinalizar');
            if (modal) modal.remove();
        }

        // ========== HELPERS ==========
        function mostrarMensagem(texto, tipo) {
            const msg = document.createElement('div');
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

        // ========== INICIALIZAÇÃO ==========
        document.addEventListener('DOMContentLoaded', function () {
            console.log('=== PÁGINA CARREGADA ===');
            carregarCarrinho();
            renderizarCarrinho();
        });
    </script>
</body>

</html>