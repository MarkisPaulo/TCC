<?php 
  require_once("verificaautenticacao.php");
  require_once("conexao.php");
  
  // Vendas de Hoje
  $sqlVendasHoje = "SELECT SUM(valorTotal) as total, COUNT(*) as quantidade 
                    FROM vendas 
                    WHERE DATE(`data/hora`) = CURDATE() AND status = 1";
  $resultVendasHoje = mysqli_query($conexao, $sqlVendasHoje);
  $vendasHoje = mysqli_fetch_assoc($resultVendasHoje);
  
  // Vendas de Ontem para comparação
  $sqlVendasOntem = "SELECT SUM(valorTotal) as total 
                     FROM vendas 
                     WHERE DATE(`data/hora`) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND status = 1";
  $resultVendasOntem = mysqli_query($conexao, $sqlVendasOntem);
  $vendasOntem = mysqli_fetch_assoc($resultVendasOntem);
  
  // Calcula variação percentual
  $totalHoje = $vendasHoje['total'] ?? 0;
  $totalOntem = $vendasOntem['total'] ?? 0;

  // Evitar divisão por zero
  if ($totalOntem > 0) {
      $variacaoVendas = (($totalHoje - $totalOntem) / $totalOntem) * 100;
  } else {
      $variacaoVendas = ($totalHoje > 0) ? 100 : 0;
  }
  
  // Produtos em Estoque
  $sqlProdutosEstoque = "SELECT COUNT(*) as total 
                         FROM produto 
                         WHERE status = 1 AND quantEstoque > 0";
  $resultProdutosEstoque = mysqli_query($conexao, $sqlProdutosEstoque);
  $produtosEstoque = mysqli_fetch_assoc($resultProdutosEstoque);
  
  // Produtos com estoque baixo (menos de 10 unidades)
  $sqlEstoqueBaixo = "SELECT COUNT(*) as total 
                      FROM produto 
                      WHERE status = 1 AND quantEstoque > 0 AND quantEstoque < 10";
  $resultEstoqueBaixo = mysqli_query($conexao, $sqlEstoqueBaixo);
  $estoqueBaixo = mysqli_fetch_assoc($resultEstoqueBaixo);
  
  $totalProdutos = $produtosEstoque['total'] ?? 0;
  $totalEstoqueBaixo = $estoqueBaixo['total'] ?? 0;
  $percentualEstoqueBaixo = $totalProdutos > 0 ? ($totalEstoqueBaixo / $totalProdutos) * 100 : 0;
  
  // Entregas Pendentes
  $sqlEntregasPendentes = "SELECT COUNT(*) as total 
                           FROM vendas 
                           WHERE status = 1 AND statusEntrega = 1";
  $resultEntregasPendentes = mysqli_query($conexao, $sqlEntregasPendentes);
  $entregasPendentes = mysqli_fetch_assoc($resultEntregasPendentes);
  
/** 
  $sqlEntregasOntem = "SELECT COUNT(*) as total 
                       FROM vendas 
                       WHERE DATE(`data/hora`) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) 
                       AND status = 1 AND statusEntrega = 1";
  $resultEntregasOntem = mysqli_query($conexao, $sqlEntregasOntem);
  $entregasOntem = mysqli_fetch_assoc($resultEntregasOntem);
  
  $totalEntregas = $entregasPendentes['total'] ?? 0;
  $totalEntregasOntem = $entregasOntem['total'] ?? 1;
  $variacaoEntregas = (($totalEntregas - $totalEntregasOntem) / $totalEntregasOntem) * 100;*/
  
  // ========== ATIVIDADES RECENTES ==========
  $sqlAtividades = "SELECT 
                        v.numeroDaVenda,
                        v.valorTotal,
                        v.`data/hora`,
                        c.nome as cliente,
                        'venda' as tipo
                    FROM vendas v
                    INNER JOIN cliente c ON v.idCliente = c.codigo
                    WHERE v.status = 1
                    ORDER BY v.`data/hora` DESC
                    LIMIT 4";
  $resultAtividades = mysqli_query($conexao, $sqlAtividades);
  $atividades = [];
  while ($row = mysqli_fetch_assoc($resultAtividades)) {
      $atividades[] = $row;
  }
  
  $sqlAlertasEstoque = "SELECT 
                            p.codigo,
                            p.nome,
                            p.quantEstoque,
                            m.nome as marca
                        FROM produto p
                        INNER JOIN marca m ON p.idMarca = m.codigo
                        WHERE p.status = 1 AND p.quantEstoque < 10
                        ORDER BY p.quantEstoque ASC
                        LIMIT 5";
  $resultAlertasEstoque = mysqli_query($conexao, $sqlAlertasEstoque);
  $alertasEstoque = [];
  while ($row = mysqli_fetch_assoc($resultAlertasEstoque)) {
      $alertasEstoque[] = $row;
  }
  
  function tempoDecorrido($dataHora) {
      $agora = new DateTime();
      $data = new DateTime($dataHora);
      $diff = $agora->diff($data);
      
      if ($diff->d > 0) {
          return $diff->d . ' dia' . ($diff->d > 1 ? 's' : '') . ' atrás';
      } elseif ($diff->h > 0) {
          return $diff->h . ' hora' . ($diff->h > 1 ? 's' : '') . ' atrás';
      } else {
          return $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '') . ' atrás';
      }
  }
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/adm.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/notificacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Dashboard - NEXUS</title>
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<body>
    <?php include('header.php'); ?>

    <div class="main-content">
        <!-- Seção de Boas-vindas -->
        <section class="welcome-section">
            <h1 class="welcome-title">Bem-vindo, <?= $_SESSION['nome'] ?>!</h1>
            <p class="welcome-subtitle">Acompanhe o desempenho da sua loja e gerencie seu negócio com facilidade</p>
        </section>

        <!-- Estatísticas Rápidas -->
        <div class="quick-stats">
            <div class="stat-card">
                <h3><i class="fas fa-shopping-cart"></i> Vendas Hoje</h3>
                <div class="stat-value">R$ <?= number_format($totalHoje, 2, ',', '.') ?></div>
                <div class="stat-diff <?= $variacaoVendas >= 0 ? 'positive' : 'negative' ?>">
                    <i class="fas fa-arrow-<?= $variacaoVendas >= 0 ? 'up' : 'down' ?>"></i> 
                    <?= number_format(abs($variacaoVendas), 1) ?>% em relação a ontem
                </div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-boxes"></i> Produtos em Estoque</h3>
                <div class="stat-value"><?= $totalProdutos ?></div>
                <div class="stat-diff <?= $percentualEstoqueBaixo > 10 ? 'negative' : 'positive' ?>">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <?= $totalEstoqueBaixo ?> com estoque baixo
                </div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-clipboard-list"></i> Entregas Pendentes</h3>
                <div class="stat-value"><?= $totalEntregas ?></div>
                <div class="stat-diff <?= $variacaoEntregas <= 0 ? 'positive' : 'negative' ?>">
                    <i class="fas fa-arrow-<?= $variacaoEntregas <= 0 ? 'down' : 'up' ?>"></i> 
                    <?= number_format(abs($variacaoEntregas), 1) ?>% em relação a ontem
                </div>
            </div>
        </div>

        <!-- Seções em Duas Colunas -->
        <div class="two-columns">
            <!-- Atividades Recentes -->
            <section class="recent-activity">
                <h2 class="section-title"><i class="fas fa-history"></i> Atividades Recentes</h2>
                <ul class="activity-list">
                    <?php if (empty($atividades)) { ?>
                        <li class="activity-item">
                            <div class="activity-details">
                                <strong>Nenhuma atividade recente</strong>
                                <div class="activity-time">Comece realizando vendas!</div>
                            </div>
                        </li>
                    <?php } else { ?>
                        <?php foreach ($atividades as $atividade) { ?>
                            <li class="activity-item">
                                <div class="activity-icon"><i class="fas fa-check"></i></div>
                                <div class="activity-details">
                                    <strong>Venda #<?= $atividade['numeroDaVenda'] ?> concluída</strong> - 
                                    R$ <?= number_format($atividade['valorTotal'], 2, ',', '.') ?>
                                    <br>
                                    <small>Cliente: <?= $atividade['cliente'] ?></small>
                                    <div class="activity-time"><?= tempoDecorrido($atividade['data/hora']) ?></div>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <a href="vendas-listar.php" style="display: inline-block; margin-top: 15px; color: #0077b6; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i> Ver todas as vendas
                </a>
            </section>

            <!-- Alertas de Estoque -->
            <section class="inventory-alerts">
                <h2 class="section-title"><i class="fas fa-bell"></i> Alertas de Estoque</h2>
                <?php if (empty($alertasEstoque)) { ?>
                    <div class="alert-item" style="border: none; color: #28a745;">
                        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                        <span>Todos os produtos estão com estoque adequado!</span>
                    </div>
                <?php } else { ?>
                    <?php foreach ($alertasEstoque as $alerta) { ?>
                        <div class="alert-item">
                            <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <span>
                                <strong><?= $alerta['nome'] ?></strong> (<?= $alerta['marca'] ?>)
                                <br>
                                <small>Apenas <?= $alerta['quantEstoque'] ?> unidade<?= $alerta['quantEstoque'] > 1 ? 's' : '' ?> em estoque</small>
                            </span>
                        </div>
                    <?php } ?>
                <?php } ?>
                <a href="produto-listar.php" style="display: inline-block; margin-top: 15px;">
                    <button class="btn btn-primary">
                        <i class="fas fa-list"></i> Gerenciar Produtos
                    </button>
                </a>
            </section>
        </div>
        
        <!-- Ações Rápidas -->
        <section class="recent-activity" style="margin-top: 30px;">
            <h2 class="section-title"><i class="fas fa-bolt"></i> Ações Rápidas</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
                <a href="venda.php" style="text-decoration: none;">
                    <button class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-cart-plus"></i> Nova Venda
                    </button>
                </a>
                <a href="cliente-cadastrar.php" style="text-decoration: none;">
                    <button class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-user-plus"></i> Novo Cliente
                    </button>
                </a>
                <a href="produto-cadastrar.php" style="text-decoration: none;">
                    <button class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-box"></i> Novo Produto
                    </button>
                </a>
                <a href="entregas-listar.php" style="text-decoration: none;">
                    <button class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-truck"></i> Entregas
                    </button>
                </a>
            </div>
        </section>
    </div>

</body>

</html>