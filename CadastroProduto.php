<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastroProd.css">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-box"></i> Cadastro de Produto</h1>
            <p>Preencha os dados abaixo para cadastrar um novo produto</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form>
            <div class="form-group">
                <label for="product-name">Nome do Produto*</label>
                <input type="text" id="product-name" placeholder="Digite o nome do produto" required>
            </div>

            <div class="form-group">
                <label for="product-description">Descrição*</label>
                <textarea id="product-description" placeholder="Descreva o produto em detalhes" required></textarea>
            </div>



            <div class="form-row">
                <div class="form-group">
                    <label>Status do Produto</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="status-ativo" name="product-status" value="ativo" checked>
                            <label for="status-ativo">Ativo</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="status-inativo" name="product-status" value="inativo">
                            <label for="status-inativo">Inativo</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product-price">Preço Unitário da Compra*</label>
                    <input type="number" id="product-price" placeholder="R$ 0,00" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="product-price2">Preço Unitário da Venda*</label>
                    <input type="number" id="product-price" placeholder="R$ 0,00" step="0.01" min="0" required>
                </div>


            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="product-category">Categoria*</label>
                    <select id="product-category" required>
                        <option value="">Selecione uma categoria</option>
                        <option value="hidraulico">Hidráulico</option>
                        <option value="eletrica">Elétrica</option>
                        <option value="impermeabilizante">Impermeabilizantes</option>
                        <option value="fixador">Fixadores</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product-stock">Estoque*</label>
                    <input type="number" id="product-stock" placeholder="Quantidade disponível" min="0" required>
                </div>
                <div class="form-group">
                    <label for="product-brand">Marca</label>
                    <input type="text" id="product-brand" placeholder="Marca do produto">
                </div>
            </div>



            <div class="form-group">
                <label for="product-sku">SKU/Código</label>
                <input type="text" id="product-sku" placeholder="Código único do produto">
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn">Cadastrar Produto</button>
            </div>
        </form>
    </div>
</body>

</html>