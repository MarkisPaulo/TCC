<?php 
  require_once("verificaautenticacao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/adm.css">
    <title>Adm</title>
</head>

<body>
    <?php include('header.php'); ?>

    <div class="main-content">
        <!-- Seção de Boas-vindas -->
        <section class="welcome-section">
            <h1 class="welcome-title">Bem-vindo ao Sistema de Gestão</h1>
            <p class="welcome-subtitle">Acompanhe o desempenho da sua loja e gerencie seu negócio com facilidade</p>
            <button class="btn btn-primary"><i class="fas fa-play"></i> Começar Tour</button>
        </section>

        <!-- Estatísticas Rápidas -->
        <div class="quick-stats">
            <div class="stat-card">
                <h3><i class="fas fa-shopping-cart"></i> Vendas Hoje</h3>
                <div class="stat-value">R$ 3.245,80</div>
                <div class="stat-diff positive"><i class="fas fa-arrow-up"></i> 12% em relação a ontem</div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-users"></i> Clientes Novos</h3>
                <div class="stat-value">18</div>
                <div class="stat-diff positive"><i class="fas fa-arrow-up"></i> 5% na semana</div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-boxes"></i> Produtos em Estoque</h3>
                <div class="stat-value">542</div>
                <div class="stat-diff negative"><i class="fas fa-arrow-down"></i> 3% em estoque</div>
            </div>
            <div class="stat-card">
                <h3><i class="fas fa-clipboard-list"></i> Pedidos Pendentes</h3>
                <div class="stat-value">7</div>
                <div class="stat-diff negative"><i class="fas fa-arrow-down"></i> 2 em relação a ontem</div>
            </div>
        </div>

        <!-- Seções em Duas Colunas -->
        <div class="two-columns">
            <!-- Atividades Recentes -->
            <section class="recent-activity">
                <h2 class="section-title"><i class="fas fa-history"></i> Atividades Recentes</h2>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon"><i class="fas fa-check"></i></div>
                        <div class="activity-details">
                            <strong>Venda concluída</strong> - R$ 450,00
                            <div class="activity-time">10 minutos atrás</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon"><i class="fas fa-plus"></i></div>
                        <div class="activity-details">
                            <strong>Novo produto cadastrado</strong> - Smartphone XYZ
                            <div class="activity-time">35 minutos atrás</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon"><i class="fas fa-exclamation"></i></div>
                        <div class="activity-details">
                            <strong>Estoque baixo</strong> - Fone de Ouvido ABC
                            <div class="activity-time">1 hora atrás</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon"><i class="fas fa-user-plus"></i></div>
                        <div class="activity-details">
                            <strong>Novo cliente cadastrado</strong> - Maria Silva
                            <div class="activity-time">2 horas atrás</div>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- Alertas de Estoque -->
            <section class="inventory-alerts">
                <h2 class="section-title"><i class="fas fa-bell"></i> Alertas de Estoque</h2>
                <div class="alert-item">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span>Fone de Ouvido ABC - apenas 2 unidades</span>
                </div>
                <div class="alert-item">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span>Carregador Tipo C - abaixo do estoque mínimo</span>
                </div>
                <div class="alert-item">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span>Película para Vidro - reposição necessária</span>
                </div>
                <div class="alert-item">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span>Cabo USB - estoque crítico</span>
                </div>
                <button class="btn btn-primary" style="margin-top: 15px;"><i class="fas fa-list"></i> Ver Todos os Alertas</button>
            </section>
        </div>
    </div>

    <script>
        // Atualizar horário das atividades
        document.addEventListener('DOMContentLoaded', function() {
            const timeElements = document.querySelectorAll('.activity-time');
            const now = new Date();

            timeElements.forEach(el => {
                const hoursAgo = Math.floor(Math.random() * 5) + 1;
                const eventTime = new Date(now.getTime() - hoursAgo * 60 * 60 * 1000);
                el.textContent = formatTimeAgo(eventTime);
            });

            function formatTimeAgo(date) {
                const diff = Math.floor((now - date) / 1000 / 60); // diferença em minutos

                if (diff < 60) {
                    return `${diff} minutos atrás`;
                } else if (diff < 1440) {
                    return `${Math.floor(diff / 60)} horas atrás`;
                } else {
                    return `${Math.floor(diff / 1440)} dias atrás`;
                }
            }

            // Simular dados dinâmicos
            setInterval(() => {
                const stats = document.querySelectorAll('.stat-value');
                stats[0].textContent = `R$ ${(Math.random() * 5000 + 2000).toFixed(2).replace('.', ',')}`;
                stats[1].textContent = Math.floor(Math.random() * 30 + 5);
            }, 5000);
        });
    </script>
</body>

</html>