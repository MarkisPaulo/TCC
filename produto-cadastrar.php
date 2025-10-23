<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];
    $precoUnitarioDaCompra = $_POST['precoUnitarioDaCompra'];
    $precoUnitarioDaVenda = $_POST['precoUnitarioDaVenda'];
    $quantEstoque = $_POST['quantEstoque'];
    $ncm = $_POST['ncm'];
    $cfop = $_POST['cfop'];

    $sql = "INSERT INTO produto (nome, precoUnitarioDaVenda, precoUnitarioDaCompra, quantEstoque, ncm, cfop ) 
    VALUES('$nome', '$precoUnitarioDaCompra', '$precoUnitarioDaCompra', '$quantEstoque', '$ncm', '$cfop')";

    mysqli_query($conexao, $sql);
    echo "Registro salvo com sucesso";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
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
                <label for="nome">Nome do Produto*</label>
                <input type="text" id="nome" placeholder="Digite o nome do produto" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="precoUnitarioDaCompra">Preço Unitário da Compra*</label>
                    <input type="number" id="precoUnitarioDaCompra" placeholder="R$ 0,00" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="precoUnitarioDaVenda">Preço Unitário da Venda*</label>
                    <input type="number" id="precoUnitarioDaVenda" placeholder="R$ 0,00" step="0.01" min="0" required>
                </div>




            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="product-category">Categoria*</label>
                    <select id="product-category" required>
                        <option value="">Selecione uma categoria</option>
                        
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantEstoque">Quantidade em Estoque*</label>
                    <input type="number" id="quantEstoque" placeholder="Quantidade disponível" min="1" required>
                </div>
                <div class="form-group">
                    <label for="product-brand">Marca*</label>
                    <input type="text" id="product-brand" placeholder="Marca do produto" required>
                </div>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label for="ncm">NCM*</label>
                    <input type="number" name="ncm" id="product-ncm" placeholder="Nomenclatura Comum do Mercosul" required>
                </div>
                <div class="form-group">
                    <label for="cfop">CFOP*</label>
                    <input type="number" name="product-cfop" id="cfop" placeholder="Código Fiscal de Operações e Prestações" required>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn">Cadastrar Produto</button>
            </div>
        </form>
    </div>
</body>

</html>