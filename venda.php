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
</head>
<body>
    <?php require_once("header.php");?>
    <div class="pdv-container">
        <!-- Painel Esquerdo -->
        <div class="left-panel">
            <div class="panel-header">
                <h2>Sistema de Vendas</h2>
                <p class="venda-numero">Venda - Pedido #348</p>
            </div>

            <div class="tabs">
                <button class="tab active">Produto</button>
                <button class="tab">Cliente</button>
                <button class="tab">Pagamento</button>
            </div>

            <div class="panel-content">

                <div class="form-group">
                    <label>Buscar Produto</label>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Digite o nome ou código do produto">
                    </div>
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
        // Toggle das abas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Atualizar total dinamicamente
        function atualizarTotal() {
            let total = 0;
            document.querySelectorAll('.product-item').forEach(item => {
                const subtotal = parseFloat(item.querySelector('.price:nth-child(4)').textContent.replace('R$ ', '').replace(',', '.'));
                total += subtotal;
            });
            document.querySelector('.total-value').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }

        // Simular adição de produtos
        document.addEventListener('DOMContentLoaded', atualizarTotal);
    </script>
</body>
</html>