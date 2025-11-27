<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

$sqlNum = "SELECT MAX(numeroDaVenda) AS maxVenda FROM vendas";
$resultadoNum = mysqli_query($conexao, $sqlNum);
$rowNum = mysqli_fetch_array($resultadoNum);
$numeroDaVenda = ($rowNum['maxVenda'] ?? 0) + 1;

// Busca produtos quando o formulário for enviado
$produtosEncontrados = [];
$textoBusca = '';

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $textoBusca = $_GET['busca'];

    // Versão simplificada (escapa a entrada)
    $textoBuscaEscaped = mysqli_real_escape_string($conexao, $textoBusca);
    $sql = "SELECT p.codigo, p.nome, p.precoUnitarioDaVenda, p.quantEstoque, m.nome as marca 
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
                                value="<?= htmlspecialchars($textoBusca) ?>" autofocus>
                        </div>
                    </form>

                    <!-- Resultados da busca -->
                    <?php if (!empty($textoBusca)): ?>
                        <div class="search-results">
                            <?php if (count($produtosEncontrados) > 0): ?>
                                <?php foreach ($produtosEncontrados as $produto): ?>
                                    <div class="result-item">
                                        <h4><?= htmlspecialchars($produto['nome']) ?></h4>
                                        <p>
                                            Código: <?= $produto['codigo'] ?> |
                                            <?= htmlspecialchars($produto['marca']) ?> |
                                            Estoque: <?= $produto['quantEstoque'] ?>
                                        </p>
                                        <p class="result-price">
                                            R$ <?= number_format($produto['precoUnitarioDaVenda'], 2, ',', '.') ?>
                                        </p>

                                        <!-- Botão para adicionar o produto -->
                                        <form method="POST" action="adicionar_item.php" style="display: inline;">
                                            <input type="hidden" name="codigo_produto" value="<?= $produto['codigo'] ?>">
                                            <input type="hidden" name="numero_venda" value="<?= $numeroDaVenda ?>">
                                            <button type="submit" class="btn-adicionar">
                                                <i class="fas fa-plus"></i> Adicionar
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-results">
                                    Nenhum produto encontrado para "<?= htmlspecialchars($textoBusca) ?>"
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
                    <button class="btn-new-sale">
                        <i class="fas fa-plus"></i> Nova Venda
                    </button>
                </div>
            </div>

            <div class="products-header">
                <div>Produto</div>
                <div style="text-align: center;">Qtde.</div>
                <div style="text-align: right;">Preço</div>
                <div style="text-align: right;">Subtotal</div>
                <div style="text-align: right;">Ações</div>
            </div>

            <div class="products-list">
                <div class="product-item">
                    <div class="product-info">
                        <h4>Vergalhão 8mm CA50</h4>
                        <p>Código: 001 | Gerdau</p>
                        <div class="product-toggle">
                        </div>
                    </div>
                    <div class="quantity">3</div>
                    <div class="price">R$ 35,00</div>
                    <div class="price">R$ 105,00</div>
                    <div class="product-actions">
                        <button class="action-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>

            <div class="total-section">
                <div class="total-label">Total da Venda</div>
                <div class="total-value">R$ 555,00</div>
            </div>
        </div>

    </div>

    <script>
        // Apenas o toggle das abas (bem simples)
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>

</html>