<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

// Ação de cancelar venda
if (isset($_GET['cancelar']) && isset($_GET['codigo'])) {
    $codigoVenda = (int) $_GET['codigo'];

    // Atualiza status da venda
    $sqlCancelar = "UPDATE vendas SET status = 0 WHERE numeroDaVenda = $codigoVenda";
    mysqli_query($conexao, $sqlCancelar);

    // Devolve produtos ao estoque
    $sqlProdutos = "SELECT FkCodigoProduto, quantidade FROM vendahasproduto WHERE FkNumeroDaVenda = $codigoVenda";
    $resultProdutos = mysqli_query($conexao, $sqlProdutos);

    while ($produto = mysqli_fetch_assoc($resultProdutos)) {
        $sqlEstoque = "UPDATE produto SET quantEstoque = quantEstoque + {$produto['quantidade']} WHERE codigo = {$produto['FkCodigoProduto']}";
        mysqli_query($conexao, $sqlEstoque);
    }

    $mensagem = "Venda #$codigoVenda cancelada com sucesso!";
}

// Modal de visualização de itens
if (isset($_GET['ver_itens'])) {
    $numeroVenda = (int)$_GET['ver_itens'];
    
    $sqlItens = "SELECT 
        vp.quantidade,
        vp.precoUnitDaVenda,
        p.codigo,
        p.nome,
        m.nome as marca
    FROM vendahasproduto vp
    INNER JOIN produto p ON vp.FkCodigoProduto = p.codigo
    INNER JOIN marca m ON p.idMarca = m.codigo
    WHERE vp.FkNumeroDaVenda = $numeroVenda";
    
    $resultItens = mysqli_query($conexao, $sqlItens);
    $itensVenda = [];
    while ($item = mysqli_fetch_assoc($resultItens)) {
        $itensVenda[] = $item;
    }
}

// Filtros
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '1';
$filtroPeriodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';
$buscaCliente = isset($_GET['busca']) ? $_GET['busca'] : '';

// Query base
$sql = "SELECT 
    v.numeroDaVenda,
    v.`data/hora` as dataHora,
    v.valorTotal,
    v.formaDeRecebimento,
    v.observacoes,
    v.status,
    v.statusEntrega,
    v.dataHoraEntrega    as dataHoraEntrega,
    c.nome as nomeCliente,
    c.codigo as codigoCliente,
    f.nome as nomeFuncionario,
    (SELECT COUNT(*) FROM vendahasproduto WHERE FkNumeroDaVenda = v.numeroDaVenda) as quantidadeItens
FROM vendas v
INNER JOIN cliente c ON v.idCliente = c.codigo
INNER JOIN funcionario f ON v.idFuncionario = f.codigo
WHERE 1=1";

// Aplica filtros
if ($filtroStatus !== '') {
    $sql .= " AND v.status = $filtroStatus";
}

if ($buscaCliente !== '') {
    $sql .= " AND (c.nome LIKE '%$buscaCliente%' OR c.codigo LIKE '%$buscaCliente%' OR v.numeroDaVenda LIKE '%$buscaCliente%')";
}

if ($filtroPeriodo == 'hoje') {
    $sql .= " AND DATE(v.`data/hora`) = CURDATE()";
} elseif ($filtroPeriodo == 'semana') {
    $sql .= " AND DATE(v.`data/hora`) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
} elseif ($filtroPeriodo == 'mes') {
    $sql .= " AND MONTH(v.`data/hora`) = MONTH(CURDATE()) AND YEAR(v.`data/hora`) = YEAR(CURDATE())";
}

$sql .= " ORDER BY v.numeroDaVenda ASC";
$resultado = mysqli_query($conexao, $sql);

// Calcula totais
$sqlTotais = "SELECT 
    SUM(CASE WHEN status = 1 THEN valorTotal ELSE 0 END) as totalVendas,
    COUNT(CASE WHEN status = 1 THEN 1 END) as quantidadeVendas,
    COUNT(CASE WHEN status = 0 THEN 1 END) as vendasCanceladas
FROM vendas";
$resultTotais = mysqli_query($conexao, $sqlTotais);
$totais = mysqli_fetch_assoc($resultTotais);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Vendas</title>
    <link rel="stylesheet" href="assets/css/listar.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/filtro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .item-venda {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }

        .item-venda:hover {
            background: #f8f9fa;
        }

        .item-header {
            font-weight: 600;
            background: #e6f2fa;
            padding: 12px 15px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <?php require_once("header.php"); ?>

    <?php if (isset($mensagem)) { ?>
        <div class="alert" role="alert">
            <?= $mensagem ?>
        </div>
    <?php } ?>

    <div class="container">
        <!-- Cards de Estatísticas -->
        <div class="stats-cards">
            <div class="stat-card recebido">
                <h3><i class="fas fa-dollar-sign"></i> Total em Vendas</h3>
                <div class="value">R$ <?= number_format($totais['totalVendas'] ?? 0, 2, ',', '.') ?></div>
            </div>
            <div class="stat-card pendente">
                <h3><i class="fas fa-shopping-cart"></i> Quantidade de Vendas</h3>
                <div class="value"><?= $totais['quantidadeVendas'] ?? 0 ?></div>
            </div>
            <div class="stat-card" style="border-color: #dc3545;">
                <h3><i class="fas fa-times-circle"></i> Vendas Canceladas</h3>
                <div class="value" style="color: #dc3545;"><?= $totais['vendasCanceladas'] ?? 0 ?></div>
            </div>
        </div>

        <!-- Barra de Filtros -->
        <div class="filter-bar">
            <form method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%;">
                <input type="text" name="busca" placeholder="Pesquisar por cliente, código da venda..."
                    value="<?= $buscaCliente ?>"
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; flex: 1; min-width: 250px;">

                <select name="status">
                    <option value="1" <?= $filtroStatus === '1' ? 'selected' : '' ?>>Todas as Vendas</option>
                    <option value="0" <?= $filtroStatus === '0' ? 'selected' : '' ?>>Vendas Canceladas</option>
                </select>

                <select name="periodo">
                    <option value="">Todos os Períodos</option>
                    <option value="hoje" <?= $filtroPeriodo === 'hoje' ? 'selected' : '' ?>>Hoje</option>
                    <option value="semana" <?= $filtroPeriodo === 'semana' ? 'selected' : '' ?>>Últimos 7 Dias</option>
                    <option value="mes" <?= $filtroPeriodo === 'mes' ? 'selected' : '' ?>>Este Mês</option>
                </select>

                <button type="submit">
                    <i class="fas fa-filter"></i> Filtrar
                </button>

                <a href="vendas-listar.php"
                    style="padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px;">
                    <i class="fas fa-redo"></i> Limpar
                </a>
            </form>
        </div>

        <!-- Tabela de Vendas -->
        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    <i class="fas fa-shopping-cart"></i> Gestão de Vendas
                    <a href="venda.php">
                        <i class="fas fa-solid fa-circle-plus"></i> Nova Venda
                    </a>
                </h5>
            </div>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th>Nº Venda</th>
                    <th>Data/Hora</th>
                    <th>Cliente</th>
                    <th>Funcionário</th>
                    <th>Qtd. Itens</th>
                    <th>Valor Total</th>
                    <th>Forma Pgto</th>
                    <th>Entrega</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($venda = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <td>#<?= $venda['numeroDaVenda'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($venda['dataHora'])) ?></td>
                        <td><?= $venda['nomeCliente'] ?></td>
                        <td><?= $venda['nomeFuncionario'] ?></td>
                        <td><?= $venda['quantidadeItens'] ?></td>
                        <td>R$ <?= number_format($venda['valorTotal'], 2, ',', '.') ?></td>
                        <td><?= $venda['formaDeRecebimento'] ?></td>
                        <td>
                            <?php if ($venda['dataHoraEntrega'] && $venda['dataHoraEntrega'] != '0000-00-00 00:00:00') { ?>
                                <?php if ($venda['statusEntrega'] == 1) { ?>
                                    <span class="status-badge pendente">
                                        <i class="fas fa-clock"></i> Agendada
                                    </span>
                                <?php } else { ?>
                                    <span class="status-badge pago">
                                        <i class="fas fa-check"></i> Entregue
                                    </span>
                                <?php } ?>
                            <?php } else { ?>
                                <span style="color: #999; font-size: 12px;">Sem entrega</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($venda['status'] == 1) { ?>
                                <span class="status-badge pago">
                                    <i class="fas fa-check"></i> Finalizada
                                </span>
                            <?php } else { ?>
                                <span class="status-badge vencido">
                                    <i class="fas fa-times"></i> Cancelada
                                </span>
                            <?php } ?>
                        </td>
                        <td class="actions">
                            <button onclick="verItens(<?= $venda['numeroDaVenda'] ?>)" class="btn btn-warning"
                                style="margin-bottom: 5px;">
                                <i class="fas fa-eye"></i> Ver Itens
                            </button>
                            <?php if ($venda['status'] == 1) { ?>
                                <a href="vendas-listar.php?cancelar=1&codigo=<?= $venda['numeroDaVenda'] ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Tem certeza que deseja cancelar esta venda? O estoque será devolvido.')">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Ver Itens -->
    <?php if (isset($_GET['ver_itens'])) { ?>
    <div id="modalItens" class="modal-overlay" style="display: flex;">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-box"></i> Itens da Venda #<?= $_GET['ver_itens'] ?></h2>
                <a href="vendas-listar.php<?= isset($_GET['status']) ? '?status='.$_GET['status'] : '' ?><?= isset($_GET['periodo']) ? (isset($_GET['status']) ? '&' : '?').'periodo='.$_GET['periodo'] : '' ?><?= isset($_GET['busca']) ? (isset($_GET['status']) || isset($_GET['periodo']) ? '&' : '?').'busca='.$_GET['busca'] : '' ?>" class="modal-close">&times;</a>
            </div>
            <div id="conteudoItens">
                <div class="item-venda item-header">
                    <div>Produto</div>
                    <div style="text-align: center;">Quantidade</div>
                    <div style="text-align: right;">Preço Unit.</div>
                    <div style="text-align: right;">Subtotal</div>
                </div>
                
                <?php 
                $total = 0;
                foreach ($itensVenda as $item) { 
                    $subtotal = $item['quantidade'] * $item['precoUnitDaVenda'];
                    $total += $subtotal;
                ?>
                    <div class="item-venda">
                        <div><strong><?= $item['nome'] ?></strong><br><small>Código: <?= $item['codigo'] ?></small></div>
                        <div style="text-align: center;"><?= $item['quantidade'] ?></div>
                        <div style="text-align: right;">R$ <?= number_format($item['precoUnitDaVenda'], 2, ',', '.') ?></div>
                        <div style="text-align: right;"><strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong></div>
                    </div>
                <?php } ?>
                
                <div style="margin-top: 20px; padding: 15px; background: #e6f2fa; border-radius: 6px; text-align: right;">
                    <strong style="font-size: 18px;">Total: R$ <?= number_format($total, 2, ',', '.') ?></strong>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>
        function verItens(numeroVenda) {
            // Redireciona para a mesma página com o parâmetro ver_itens
            const params = new URLSearchParams(window.location.search);
            let url = 'vendas-listar.php?ver_itens=' + numeroVenda;
            
            // Mantém os filtros ativos
            if (params.get('status')) url += '&status=' + params.get('status');
            if (params.get('periodo')) url += '&periodo=' + params.get('periodo');
            if (params.get('busca')) url += '&busca=' + params.get('busca');
            
            window.location.href = url;
        }
    </script>   
</body>

</html>