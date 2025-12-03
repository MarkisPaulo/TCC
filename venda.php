<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

// Inicializa o carrinho na sessão se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = array();
}

// Processa ações do carrinho
if (isset($_POST['acao'])) {
    switch ($_POST['acao']) {
        case 'adicionar':
            $codigo = $_POST['codigo'];
            $nome = $_POST['nome'];
            $marca = $_POST['marca'];
            $unidMedida = $_POST['unidMedida'];
            $preco = floatval($_POST['preco']);
            $quantidade = intval($_POST['quantidade']);
            $estoque = intval($_POST['estoque']);

            // Verifica se o produto já está no carrinho
            $produtoExiste = false;
            foreach ($_SESSION['carrinho'] as $key => $item) {
                if ($item['codigo'] == $codigo) {
                    $novaQuantidade = $item['quantidade'] + $quantidade;
                    if ($novaQuantidade <= $estoque) {
                        $_SESSION['carrinho'][$key]['quantidade'] = $novaQuantidade;
                        $mensagem = "Quantidade atualizada!";
                    } else {
                        $mensagem = "Estoque insuficiente!";
                    }
                    $produtoExiste = true;
                    break;
                }
            }

            if (!$produtoExiste) {
                $_SESSION['carrinho'][] = array(
                    'codigo' => $codigo,
                    'nome' => $nome,
                    'marca' => $marca,
                    'unidMedida' => $unidMedida,
                    'precoUnitario' => $preco,
                    'quantidade' => $quantidade,
                    'quantEstoque' => $estoque
                );
                $mensagem = "Produto adicionado!";
            }
            break;

        case 'remover':
            $indice = intval($_POST['indice']);
            if (isset($_SESSION['carrinho'][$indice])) {
                unset($_SESSION['carrinho'][$indice]);
                $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // Reindexar array
                $mensagem = "Produto removido!";
            }
            break;

        case 'alterar_quantidade':
            $indice = intval($_POST['indice']);
            $incremento = intval($_POST['incremento']);
            if (isset($_SESSION['carrinho'][$indice])) {
                $novaQuantidade = $_SESSION['carrinho'][$indice]['quantidade'] + $incremento;
                if ($novaQuantidade > 0 && $novaQuantidade <= $_SESSION['carrinho'][$indice]['quantEstoque']) {
                    $_SESSION['carrinho'][$indice]['quantidade'] = $novaQuantidade;
                } elseif ($novaQuantidade <= 0) {
                    unset($_SESSION['carrinho'][$indice]);
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                    $mensagem = "Produto removido!";
                } else {
                    $mensagem = "Estoque insuficiente!";
                }
            }
            break;

        case 'limpar':
            $_SESSION['carrinho'] = array();
            $mensagem = "Carrinho limpo!";
            break;
    }
}

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
    $sql = "SELECT p.codigo, p.nome, p.precoUnitarioDaVenda, p.quantEstoque, p.unidMedida, m.nome as marca 
            FROM produto p 
            INNER JOIN marca m ON p.idMarca = m.codigo 
            WHERE p.status = 1 
            AND (p.nome LIKE '%$textoBuscaProd%' OR p.codigo LIKE '%$textoBuscaProd%')
            LIMIT 10";
    $resultado = mysqli_query($conexao, $sql);
    while ($row = mysqli_fetch_assoc($resultado)) {
        $produtosEncontrados[] = $row;
    }
}

if (isset($_GET['buscaCli']) && !empty($_GET['buscaCli'])) {
    $textoBuscaCli = $_GET['buscaCli'];
    $sql = "SELECT c.codigo, c.nome, c.cpf_cnpj 
            FROM cliente c 
            WHERE c.status = 1 
            AND (c.nome LIKE '%$textoBuscaCli%' OR c.codigo LIKE '%$textoBuscaCli%')
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

// Calcula o total do carrinho
$totalCarrinho = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $totalCarrinho += $item['precoUnitario'] * $item['quantidade'];
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

    <?php if (isset($_SESSION['erro_entrega'])) { ?>
        <div
            style="position: fixed; top: 20px; right: 20px; padding: 15px 25px; background: #dc3545; color: white; border-radius: 8px; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
            <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['erro_entrega'] ?>
        </div>
        <script>
            setTimeout(function () {
                document.querySelector('[style*="position: fixed"]').style.display = 'none';
            }, 4000);
        </script>
        <?php unset($_SESSION['erro_entrega']); ?>
    <?php } ?>

    <?php if (isset($mensagem)) { ?>
        <div
            style="position: fixed; top: 20px; right: 20px; padding: 15px 25px; background: #4caf50; color: white; border-radius: 8px; z-index: 10000; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
            <?= $mensagem ?>
        </div>
        <script>
            setTimeout(function () {
                document.querySelector('[style*="position: fixed"]').style.display = 'none';
            }, 2000);
        </script>
    <?php } ?>

    <div class="pdv-container">
        <!-- Painel Esquerdo -->
        <div class="left-panel">
            <div class="panel-header">
                <h2>Sistema de Vendas</h2>
                <p class="venda-numero">Venda - Pedido #<?= $numeroDaVenda ?></p>
            </div>

            <!-- ABAS -->
            <div class="tabs">
                <button class="tab <?= $aba_ativa === 'produto' ? 'active' : '' ?>" onclick="mudarAba('produto')">
                    <i class="fas fa-box"></i> Produto
                </button>
                <button class="tab <?= $aba_ativa === 'cliente' ? 'active' : '' ?>" onclick="mudarAba('cliente')">
                    <i class="fas fa-user"></i> Cliente
                </button>
            </div>

            <!-- CONTEÚDO DAS ABAS -->
            <div class="panel-content">

                <!-- ABA PRODUTO -->
                <div class="tab-content <?= $aba_ativa === 'produto' ? 'active' : '' ?>"
                    style="<?= $aba_ativa === 'produto' ? '' : 'display: none;' ?>" id="tab-produto">
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

                                            <form method="POST" action="" class="quantidade-group">
                                                <input type="hidden" name="acao" value="adicionar">
                                                <input type="hidden" name="codigo" value="<?= $produto['codigo'] ?>">
                                                <input type="hidden" name="nome" value="<?= $produto['nome'] ?>">
                                                <input type="hidden" name="marca" value="<?= $produto['marca'] ?>">
                                                <input type="hidden" name="unidMedida" value="<?= $produto['unidMedida'] ?>">
                                                <input type="hidden" name="preco" value="<?= $produto['precoUnitarioDaVenda'] ?>">
                                                <input type="hidden" name="estoque" value="<?= $produto['quantEstoque'] ?>">

                                                <label>Qtde:</label>
                                                <input type="text" name="quantidade" value="1" min="1"
                                                    max="<?= $produto['quantEstoque'] ?>" data-mask="numerico" maxlength="5"
                                                    class="input-quantidade">
                                                <button type="submit" class="btn-adicionar">
                                                    <i class="fas fa-plus"></i> Adicionar
                                                </button>
                                            </form>
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
                    style="<?= $aba_ativa === 'cliente' ? '' : 'display: none;' ?>" id="tab-cliente">
                    <div class="form-group">
                        <label for="select-cliente">
                            <i class="fas fa-user"></i> Buscar Cliente
                        </label>
                        <form method="GET" action="">
                            <input type="hidden" name="aba" value="cliente">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" name="buscaCli" placeholder="Digite o nome ou código do Cliente"
                                    value="<?= $textoBuscaCli ?>" autofocus autocomplete="off">
                            </div>
                        </form>

                        <?php if (!empty($textoBuscaCli)) { ?>
                            <div class="search-results">
                                <?php if (count($clientesEncontrados) > 0) { ?>
                                    <?php foreach ($clientesEncontrados as $cliente) { ?>
                                        <div class="result-item" style="cursor: pointer;"
                                            onclick="selecionarCliente(<?= $cliente['codigo'] ?>, '<?= addslashes($cliente['nome']) ?>', '<?= $cliente['cpf_cnpj'] ?>')">
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
                    </div>

                    <div id="cliente-info" class="info-box"
                        style="display: <?= isset($_SESSION['cliente_venda']) ? 'block' : 'none' ?>;">
                        <h4><i class="fas fa-check-circle"></i> Cliente Selecionado</h4>
                        <p><strong>Nome:</strong> <span
                                id="cliente-nome-display"><?= $_SESSION['cliente_venda']['nome'] ?? '' ?></span></p>
                        <p><strong>CPF/CNPJ:</strong> <span
                                id="cliente-cpf-display"><?= $_SESSION['cliente_venda']['cpf_cnpj'] ?? '' ?></span></p>
                    </div>

                    <div class="form-group">
                        <a href="cliente-cadastrar.php" style="text-decoration:none;" target="_blank">
                            <button type="button" class="btn btn-secondary">
                                <i class="fas fa-user-plus"></i> Cadastrar Novo Cliente
                            </button>
                        </a>
                    </div>

                    <div class="alert-info">
                        <i class="fas fa-info-circle"></i>
                        <p>A seleção do cliente é obrigatória para finalizar a venda.</p>
                    </div>
                </div>

            </div>

            <div class="panel-actions">
                <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="acao" value="limpar">
                    <button type="submit" class="btn btn-secondary"
                        onclick="return confirm('Deseja limpar o carrinho?')">
                        <i class="fas fa-trash"></i> Limpar
                    </button>
                </form>
                <button class="btn btn-primary" onclick="abrirModalFinalizar()">
                    <i class="fas fa-check"></i> Resumo da Venda
                </button>
            </div>
        </div>

        <!-- Painel Central -->
        <div class="center-panel">
            <div class="main-header">
                <h1><i class="fas fa-shopping-cart"></i> Itens Venda</h1>
                <div class="header-actions">
                    <a href="venda.php">
                        <button class="btn-new-sale">
                            <i class="fas fa-plus"></i> Nova Venda
                        </button>
                    </a>
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

            <div class="products-list">
                <?php if (empty($_SESSION['carrinho'])) { ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart" style="font-size: 3rem; color: #ccc;"></i>
                        <p style="color: #999; margin-top: 1rem;">Nenhum produto adicionado</p>
                    </div>
                <?php } else { ?>
                    <?php foreach ($_SESSION['carrinho'] as $indice => $item) {
                        $subtotal = $item['precoUnitario'] * $item['quantidade'];
                        ?>
                        <div class="product-item">
                            <div class="product-info">
                                <h4><?= $item['nome'] ?></h4>
                                <p>Código: <?= $item['codigo'] ?> | <?= $item['marca'] ?></p>
                            </div>
                            <div class="unidMedida"><?= $item['unidMedida'] ?></div>
                            <div class="quantity">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="acao" value="alterar_quantidade">
                                    <input type="hidden" name="indice" value="<?= $indice ?>">
                                    <input type="hidden" name="incremento" value="-1">
                                    <button type="submit" class="btn-qtd-mini">-</button>
                                </form>
                                <span><?= $item['quantidade'] ?></span>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="acao" value="alterar_quantidade">
                                    <input type="hidden" name="indice" value="<?= $indice ?>">
                                    <input type="hidden" name="incremento" value="1">
                                    <button type="submit" class="btn-qtd-mini">+</button>
                                </form>
                            </div>
                            <div class="price">R$ <?= number_format($item['precoUnitario'], 2, ',', '.') ?></div>
                            <div class="price">R$ <?= number_format($subtotal, 2, ',', '.') ?></div>
                            <div class="product-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="acao" value="remover">
                                    <input type="hidden" name="indice" value="<?= $indice ?>">
                                    <button type="submit" class="action-btn" onclick="return confirm('Remover este produto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="total-section">
                <div class="total-label">Total da Venda</div>
                <div class="total-value">R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></div>
            </div>
        </div>

    </div>

    <script src="assets/js/masks.js"></script>
    <script>
        function mudarAba(aba) {
            window.location.href = 'venda.php?aba=' + aba;
        }

        function selecionarCliente(codigo, nome, cpf) {
            // Faz requisição para salvar cliente na sessão
            const formData = new FormData();
            formData.append('salvar_cliente', '1');
            formData.append('codigo', codigo);
            formData.append('nome', nome);
            formData.append('cpf_cnpj', cpf);

            fetch('salvar_cliente_sessao.php', {
                method: 'POST',
                body: formData
            })
                .then(() => {
                    document.getElementById('cliente-nome-display').textContent = nome;
                    document.getElementById('cliente-cpf-display').textContent = cpf;
                    document.getElementById('cliente-info').style.display = 'block';
                    alert('Cliente "' + nome + '" selecionado!');
                });
        }

        function abrirModalFinalizar() {
            <?php if (empty($_SESSION['carrinho'])) { ?>
                alert('Adicione produtos antes de finalizar a venda');
                return;
            <?php } ?>

            const modalHTML = `
                <div id="modalFinalizar" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
                        <h2 style="margin-bottom: 20px;">Finalizar Venda</h2>
                        <form method="POST" action="finalizar_venda.php">
                            
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Cliente*</label>
                                <select id="selectCliente" name="idCliente" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                    <option value="">Selecione o cliente</option>
                                    <?php
                                    $sqlClientesModal = "SELECT codigo, nome, endereco FROM cliente WHERE status = 1 ORDER BY nome";
                                    $resultClientesModal = mysqli_query($conexao, $sqlClientesModal);
                                    while ($cliente = mysqli_fetch_assoc($resultClientesModal)) {
                                        $selected = (isset($_SESSION['cliente_venda']) && $_SESSION['cliente_venda']['codigo'] == $cliente['codigo']) ? 'selected' : '';
                                        echo "<option value='{$cliente['codigo']}' data-endereco='{$cliente['endereco']}' {$selected}>{$cliente['nome']}</option>";
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
                                <input type="text" value="R$ <?= number_format($totalCarrinho, 2, ',', '.') ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #f5f5f5;">
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Valor Pago*</label>
                                <input type="text" name="valorPago" data-mask="valor" placeholder="R$ 0,00" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <small style="color: #666;">Se pago parcial, o restante ficará em aberto</small>
                            </div>

                            <div style="margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #007bff;">
                                <label style="display: flex; align-items: center; cursor: pointer; font-weight: 600; margin-bottom: 10px;">
                                    <input type="checkbox" id="checkEntrega" name="temEntrega" value="1" style="width: 18px; height: 18px; cursor: pointer; margin-right: 10px;">
                                    <i class="fas fa-truck" style="margin-right: 8px; color: #007bff;"></i> Agendar Entrega
                                </label>
                                
                                <div id="enderecoEntregaDiv" style="display: none;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600; margin-top: 10px;">Endereço de Entrega*</label>
                                    <textarea id="enderecoEntrega" name="enderecoEntrega" rows="3" placeholder="Endereço padrão do cliente" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                                    <small style="color: #666;">Edite se necessário</small>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Data de Entrega*</label>
                                            <input type="text" id="dataEntrega" name="dataEntrega" data-mask="data" placeholder="DD/MM/YYYY" maxlength="10" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                        </div>
                                        <div>
                                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Hora de Entrega*</label>
                                            <input type="text" id="horaEntrega" name="horaEntrega" data-mask="hora" placeholder="HH:MM" maxlength="5" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                        </div>
                                    </div>
                                </div>
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

            // Listener para o checkbox de entrega
            const checkEntrega = document.getElementById('checkEntrega');
            const enderecoEntregaDiv = document.getElementById('enderecoEntregaDiv');
            const selectCliente = document.getElementById('selectCliente');
            const enderecoEntrega = document.getElementById('enderecoEntrega');

            checkEntrega.addEventListener('change', function () {
                if (this.checked) {
                    enderecoEntregaDiv.style.display = 'block';
                    // Preenche com endereço do cliente selecionado
                    const opcaoSelecionada = selectCliente.options[selectCliente.selectedIndex];
                    enderecoEntrega.value = opcaoSelecionada.getAttribute('data-endereco') || '';
                } else {
                    enderecoEntregaDiv.style.display = 'none';
                    enderecoEntrega.value = '';
                }
            });

            selectCliente.addEventListener('change', function () {
                if (checkEntrega.checked) {
                    // Atualiza endereço quando cliente muda
                    const opcaoSelecionada = this.options[this.selectedIndex];
                    enderecoEntrega.value = opcaoSelecionada.getAttribute('data-endereco') || '';
                }
            });

            setTimeout(() => {
                if (window.MaskUtils) {
                    window.MaskUtils.initializeMasks();
                }
            }, 100);
        }

        function fecharModal() {
            const modal = document.getElementById('modalFinalizar');
            if (modal) modal.remove();
        }
    </script>
</body>

</html>