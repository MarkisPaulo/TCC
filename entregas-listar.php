<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
require_once("notificacoes.php");

date_default_timezone_set('America/Sao_Paulo');

// Ação de finalizar entrega
if (isset($_GET['finalizar']) && isset($_GET['codigo'])) {
    $codigoVenda = (int) $_GET['codigo'];

    $sqlFinalizar = "UPDATE vendas SET statusEntrega = 0 WHERE numeroDaVenda = $codigoVenda";
    mysqli_query($conexao, $sqlFinalizar);

    setNotificacao('sucesso', "Entrega #$codigoVenda finalizada com sucesso!");
    header("Location: entregas-listar.php");
    exit;
}

// Busca detalhes se solicitado
$detalhesVenda = null;
if (isset($_GET['ver_detalhes'])) {
    $numeroVenda = (int) $_GET['ver_detalhes'];

    // Busca informações da venda
    $sqlVenda = "SELECT 
        v.numeroDaVenda,
        v.valorTotal,
        v.dataHoraEntrega,
        v.enderecoEntrega,
        v.observacoes,
        c.nome as nomeCliente,
        c.telefone as telefoneCliente
    FROM vendas v
    INNER JOIN cliente c ON v.idCliente = c.codigo
    WHERE v.numeroDaVenda = $numeroVenda";

    $resultVenda = mysqli_query($conexao, $sqlVenda);
    $detalhesVenda = mysqli_fetch_assoc($resultVenda);

    // Busca os itens da venda
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
$filtroPeriodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';
$buscaCliente = isset($_GET['busca']) ? $_GET['busca'] : '';

// Query base - apenas entregas pendentes (statusEntrega = 1)
$sql = "SELECT 
    v.numeroDaVenda,
    v.`data/hora` as dataHora,
    v.valorTotal,
    v.dataHoraEntrega as dataHoraEntrega,
    v.enderecoEntrega,
    v.observacoes,
    c.nome as nomeCliente,
    c.telefone as telefoneCliente,
    c.codigo as codigoCliente,
    (SELECT COUNT(*) FROM vendahasproduto WHERE FkNumeroDaVenda = v.numeroDaVenda) as quantidadeItens
FROM vendas v
INNER JOIN cliente c ON v.idCliente = c.codigo
WHERE v.statusEntrega = 1 
AND v.status = 1
AND v.dataHoraEntrega IS NOT NULL
AND v.dataHoraEntrega != '0000-00-00 00:00:00'";

// Aplica filtros
if ($buscaCliente !== '') {
    $sql .= " AND (c.nome LIKE '%$buscaCliente%' OR c.codigo LIKE '%$buscaCliente%' OR v.numeroDaVenda LIKE '%$buscaCliente%')";
}

if ($filtroPeriodo == 'hoje') {
    $sql .= " AND DATE(v.dataHoraEntrega) = CURDATE()";
} elseif ($filtroPeriodo == 'atrasadas') {
    $sql .= " AND v.dataHoraEntrega < NOW()";
} elseif ($filtroPeriodo == 'proximas') {
    $sql .= " AND DATE(v.dataHoraEntrega) = CURDATE() OR (v.dataHoraEntrega >= NOW() AND DATE(v.dataHoraEntrega) <= DATE_ADD(CURDATE(), INTERVAL 1 DAY))";
}

$sql .= " ORDER BY v.dataHoraEntrega ASC";
$resultado = mysqli_query($conexao, $sql);

// Calcula estatísticas
$sqlStats = "SELECT 
    COUNT(*) as totalPendentes,
    COUNT(CASE WHEN `dataHoraEntrega` < NOW() THEN 1 END) as atrasadas,
    COUNT(CASE WHEN DATE(`dataHoraEntrega`) = CURDATE() THEN 1 END) as hoje
FROM vendas 
WHERE statusEntrega = 1 
AND status = 1
AND `dataHoraEntrega` IS NOT NULL
AND `dataHoraEntrega` != '0000-00-00 00:00:00'";
$resultStats = mysqli_query($conexao, $sqlStats);
$stats = mysqli_fetch_assoc($resultStats);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Entregas</title>
    <link rel="stylesheet" href="assets/css/listar.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/filtro.css">
    <link rel="stylesheet" href="assets/css/notificacoes.css">
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
            max-width: 900px;
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
            text-decoration: none;
        }

        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #0077b6;
        }

        .info-section h3 {
            margin: 0 0 10px 0;
            color: #0077b6;
            font-size: 16px;
        }

        .info-section p {
            margin: 5px 0;
            color: #333;
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

        .entrega-atrasada {
            background-color: #fff5f5 !important;
        }

        .badge-atrasada {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-hoje {
            background: #ffc107;
            color: #333;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php require_once("header.php"); ?>

    <div class="container">
        <!-- Cards de Estatísticas -->
        <div class="stats-cards">
            <div class="stat-card pendente">
                <h3><i class="fas fa-truck"></i> Entregas Pendentes</h3>
                <div class="value"><?= $stats['totalPendentes'] ?? 0 ?></div>
            </div>
            <div class="stat-card" style="border-color: #ffc107;">
                <h3><i class="fas fa-clock"></i> Entregas Hoje</h3>
                <div class="value" style="color: #ffc107;"><?= $stats['hoje'] ?? 0 ?></div>
            </div>
            <div class="stat-card" style="border-color: #dc3545;">
                <h3><i class="fas fa-exclamation-triangle"></i> Entregas Atrasadas</h3>
                <div class="value" style="color: #dc3545;"><?= $stats['atrasadas'] ?? 0 ?></div>
            </div>
        </div>

        <!-- Barra de Filtros -->
        <div class="filter-bar">
            <form method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%;">
                <input type="text" name="busca" placeholder="Pesquisar por cliente, código da venda..."
                    value="<?= $buscaCliente ?>"
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; flex: 1; min-width: 250px;">

                <select name="periodo">
                    <option value="">Todas as Entregas</option>
                    <option value="atrasadas" <?= $filtroPeriodo === 'atrasadas' ? 'selected' : '' ?>>Atrasadas</option>
                    <option value="hoje" <?= $filtroPeriodo === 'hoje' ? 'selected' : '' ?>>Hoje</option>
                    <option value="proximas" <?= $filtroPeriodo === 'proximas' ? 'selected' : '' ?>>Próximas 24h</option>
                </select>

                <button type="submit">
                    <i class="fas fa-filter"></i> Filtrar
                </button>

                <a href="entregas-listar.php"
                    style="padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px;">
                    <i class="fas fa-redo"></i> Limpar
                </a>
            </form>
        </div>

        <!-- Tabela de Entregas -->
        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    <i class="fas fa-truck"></i> Controle de Entregas
                </h5>
            </div>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th>Nº Venda</th>
                    <th>Cliente</th>
                    <th>Telefone</th>
                    <th>Endereço de Entrega</th>
                    <th>Data/Hora Entrega</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $agora = new DateTime();
                while ($entrega = mysqli_fetch_assoc($resultado)) {
                    $dataEntrega = new DateTime($entrega['dataHoraEntrega']);
                    $atrasada = $dataEntrega < $agora;
                    $hoje = $dataEntrega->format('Y-m-d') === $agora->format('Y-m-d');
                    ?>
                    <tr class="<?= $atrasada ? 'entrega-atrasada' : '' ?>">
                        <td>#<?= $entrega['numeroDaVenda'] ?></td>
                        <td><?= $entrega['nomeCliente'] ?></td>
                        <td><?= $entrega['telefoneCliente'] ?></td>
                        <td style="max-width: 250px;"><?= $entrega['enderecoEntrega'] ?></td>
                        <td>
                            <?= $dataEntrega->format('d/m/Y H:i') ?>
                            <?php if ($atrasada) { ?>
                                <br><span class="badge-atrasada"><i class="fas fa-exclamation-triangle"></i> ATRASADA</span>
                            <?php } elseif ($hoje) { ?>
                                <br><span class="badge-hoje"><i class="fas fa-clock"></i> HOJE</span>
                            <?php } ?>
                        </td>
                        <td>R$ <?= number_format($entrega['valorTotal'], 2, ',', '.') ?></td>
                        <td>
                            <span class="status-badge pendente">
                                <i class="fas fa-truck"></i> Pendente
                            </span>
                        </td>
                        <td class="actions">
                            <a href="?ver_detalhes=<?= $entrega['numeroDaVenda'] ?><?= $buscaCliente ? '&busca=' . $buscaCliente : '' ?><?= $filtroPeriodo ? '&periodo=' . $filtroPeriodo : '' ?>"
                                class="btn btn-warning"
                                style="margin-bottom: 5px; width: 100%; text-decoration: none; text-align: center;">
                                <i class="fas fa-eye"></i> Ver Detalhes
                            </a>
                            <a href="entregas-listar.php?finalizar=1&codigo=<?= $entrega['numeroDaVenda'] ?>"
                                class="btn btn-ativar" style="width: 100%; text-decoration: none; text-align: center;"
                                onclick="return confirm('Confirmar que a entrega foi realizada?')">
                                <i class="fas fa-check-circle"></i> Finalizar Entrega
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Ver Detalhes -->
    <?php if (isset($_GET['ver_detalhes']) && $detalhesVenda) {
        $dataEntregaFormatada = 'Não agendada';
        if ($detalhesVenda['dataHoraEntrega'] && $detalhesVenda['dataHoraEntrega'] != '0000-00-00 00:00:00') {
            $dataEntrega = new DateTime($detalhesVenda['dataHoraEntrega']);
            $dataEntregaFormatada = $dataEntrega->format('d/m/Y H:i');
        }
        ?>
        <div id="modalDetalhes" class="modal-overlay" style="display: flex;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><i class="fas fa-truck"></i> Detalhes da Entrega</h2>
                    <a href="entregas-listar.php<?= $buscaCliente ? '?busca=' . $buscaCliente : '' ?><?= $filtroPeriodo ? ($buscaCliente ? '&' : '?') . 'periodo=' . $filtroPeriodo : '' ?>"
                        class="modal-close">&times;</a>
                </div>
                <div id="conteudoDetalhes">
                    <!-- Informações da Entrega -->
                    <div class="info-section">
                        <h3><i class="fas fa-info-circle"></i> Informações da Entrega</h3>
                        <p><strong>Venda:</strong> #<?= $detalhesVenda['numeroDaVenda'] ?></p>
                        <p><strong>Cliente:</strong> <?= $detalhesVenda['nomeCliente'] ?></p>
                        <p><strong>Telefone:</strong> <?= $detalhesVenda['telefoneCliente'] ?></p>
                        <p><strong>Endereço:</strong> <?= $detalhesVenda['enderecoEntrega'] ?></p>
                        <p><strong>Data/Hora:</strong> <?= $dataEntregaFormatada ?></p>
                        <?php if ($detalhesVenda['observacoes']) { ?>
                            <p><strong>Observações:</strong> <?= $detalhesVenda['observacoes'] ?></p>
                        <?php } ?>
                    </div>

                    <!-- Itens da Venda -->
                    <div class="info-section">
                        <h3><i class="fas fa-box"></i> Itens da Venda</h3>
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
                                <div><strong><?= $item['nome'] ?></strong><br><small>Código: <?= $item['codigo'] ?> |
                                        <?= $item['marca'] ?></small></div>
                                <div style="text-align: center;"><?= $item['quantidade'] ?></div>
                                <div style="text-align: right;">R$ <?= number_format($item['precoUnitDaVenda'], 2, ',', '.') ?>
                                </div>
                                <div style="text-align: right;"><strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                                </div>
                            </div>
                        <?php } ?>

                        <div
                            style="margin-top: 15px; padding: 15px; background: #e6f2fa; border-radius: 6px; text-align: right;">
                            <strong style="font-size: 18px;">Valor Total: R$
                                <?= number_format($total, 2, ',', '.') ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</body>

</html>