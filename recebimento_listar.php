<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

date_default_timezone_set('America/Sao_Paulo');

// Filtros
$filtroStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filtroPeriodo = isset($_GET['periodo']) ? $_GET['periodo'] : '';
$buscaCliente = isset($_GET['buscaCliente']) ? $_GET['buscaCliente'] : '';

$sql = "SELECT 
    r.codigo,
    r.formaDeRecebimento,
    r.valorRecebido,
    r.valorReceber,
    r.dataVencimento,
    r.dataRecebimento,
    r.status,
    v.numeroDaVenda,
    v.valorTotal,
    c.nome as nomeCliente,
    c.codigo as codigoCliente
FROM recebimentos r
INNER JOIN vendas v ON r.idVenda = v.numeroDaVenda
INNER JOIN cliente c ON v.idCliente = c.codigo
WHERE 1=1";

// Aplica filtro de busca por cliente
if ($buscaCliente !== '') {
    $sql .= " AND (c.nome LIKE '%$buscaCliente%' OR c.codigo LIKE '%$buscaCliente%')";
}

// Aplica filtros
if ($filtroStatus !== '') {
    $sql .= " AND r.status = $filtroStatus";
}

if ($filtroPeriodo == 'vencidos') {
    $sql .= " AND r.dataVencimento < CURDATE() AND r.status = 0";
} elseif ($filtroPeriodo == 'hoje') {
    $sql .= " AND r.dataVencimento = CURDATE()";
} elseif ($filtroPeriodo == 'semana') {
    $sql .= " AND r.dataVencimento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
}

$sql .= " ORDER BY r.codigo ASC";
$resultado = mysqli_query($conexao, $sql);

// Calcula totais
$sqlTotais = "SELECT 
    SUM(CASE WHEN status = 1 THEN valorRecebido ELSE 0 END) as totalRecebido,
    SUM(CASE WHEN status = 0 THEN valorReceber ELSE 0 END) as totalPendente
FROM recebimentos";
$resultTotais = mysqli_query($conexao, $sqlTotais);
$totais = mysqli_fetch_assoc($resultTotais);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Recebimentos</title>
    <link rel="stylesheet" href="assets/css/listar.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/filtro.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
    
</head>
<body>
    <?php require_once("header.php"); ?>

    <div class="container">
        <!-- Cards de Estatísticas -->
        <div class="stats-cards">
            <div class="stat-card recebido">
                <h3><i class="fas fa-check-circle"></i> Total Recebido</h3>
                <div class="value">R$ <?= number_format($totais['totalRecebido'] ?? 0, 2, ',', '.') ?></div>
            </div>
            <div class="stat-card pendente">
                <h3><i class="fas fa-clock"></i> Total Pendente</h3>
                <div class="value">R$ <?= number_format($totais['totalPendente'] ?? 0, 2, ',', '.') ?></div>
            </div>
        </div>

        <!-- Barra de Filtros -->
        <div class="filter-bar">
            <form method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; width: 100%;">
                <input type="text" name="buscaCliente" placeholder="Pesquisar por cliente (nome ou código)..." value="<?= $buscaCliente ?>" style="padding: 10px; border: 1px solid #ddd; border-radius: 6px; flex: 1; min-width: 250px;">
                
                <select name="status">
                    <option value="">Todos os Status</option>
                    <option value="1" <?= $filtroStatus === '1' ? 'selected' : '' ?>>Pagos</option>
                    <option value="0" <?= $filtroStatus === '0' ? 'selected' : '' ?>>Pendentes</option>
                </select>
                
                <select name="periodo">
                    <option value="">Todos os Períodos</option>
                    <option value="vencidos" <?= $filtroPeriodo === 'vencidos' ? 'selected' : '' ?>>Vencidos</option>
                    <option value="hoje" <?= $filtroPeriodo === 'hoje' ? 'selected' : '' ?>>Vencimento Hoje</option>
                    <option value="semana" <?= $filtroPeriodo === 'semana' ? 'selected' : '' ?>>Próximos 7 Dias</option>
                </select>
                
                <button type="submit">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                
                <a href="recebimento_listar.php" style="padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 6px;">
                    <i class="fas fa-redo"></i> Limpar
                </a>
            </form>
        </div>

        <!-- Tabela de Recebimentos -->
        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    <i class="fas fa-money-bill-wave"></i> Gestão de Recebimentos
                </h5>
            </div>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Venda</th>
                    <th>Cliente</th>
                    <th>Forma Pgto</th>
                    <th>Valor Total</th>
                    <th>Valor Recebido</th>
                    <th>Valor a Receber</th>
                    <th>Vencimento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($linha = mysqli_fetch_assoc($resultado)) { 
                    $hoje = date('Y-m-d');
                    $vencido = $linha['dataVencimento'] < $hoje && $linha['status'] == 0;
                ?>
                <tr style="<?= $vencido ? 'background-color: #fff5f5;' : '' ?>">
                    <td><?= $linha['codigo'] ?></td>
                    <td>#<?= $linha['numeroDaVenda'] ?></td>
                    <td><?= $linha['nomeCliente'] ?></td>
                    <td><?= $linha['formaDeRecebimento'] ?></td>
                    <td>R$ <?= number_format($linha['valorTotal'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($linha['valorRecebido'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($linha['valorReceber'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($linha['dataVencimento'])) ?></td>
                    <td>
                        <?php if ($linha['status'] == 1) { ?>
                            <span class="status-badge pago">
                                <i class="fas fa-check"></i> Pago
                            </span>
                        <?php } elseif ($vencido) { ?>
                            <span class="status-badge vencido">
                                <i class="fas fa-exclamation-triangle"></i> Vencido
                            </span>
                        <?php } else { ?>
                            <span class="status-badge pendente">
                                <i class="fas fa-clock"></i> Pendente
                            </span>
                        <?php } ?>
                    </td>
                    <td class="actions">
                        <?php if ($linha['status'] == 0) { ?>
                            <a href="recebimento_registrar.php?codigo=<?= $linha['codigo'] ?>" class="btn-receber">
                                <i class="fas fa-dollar-sign"></i> Receber
                            </a>
                        <?php } else { ?>
                            <span style="color: #28a745; font-size: 12px;">
                                <i class="fas fa-check-circle"></i> Recebido em <?= date('d/m/Y', strtotime($linha['dataRecebimento'])) ?>
                            </span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>