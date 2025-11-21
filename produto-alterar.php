<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {


    $nome = $_POST['nome'];
    $precoUnitarioDaCompra = $_POST['precoUnitarioDaCompra'];
    $precoUnitarioDaVenda = $_POST['precoUnitarioDaVenda'];
    $quantEstoque = $_POST['quantEstoque'];
    $ncm = $_POST['ncm'];
    $cfop = $_POST['cfop'];
    $unidMeidida = $_POST['unidMedida'];
    $idMarca = $_POST['idMarca'];
    $idCategoria = $_POST['idCategoria'];
    $status = $_POST['status'];


    $sql = "UPDATE produto SET nome = '$nome', status = '$status', precoUnitarioDaCompra = '$precoUnitarioDaCompra', precoUnitarioDaVenda = '$precoUnitarioDaVenda',
        quantEstoque = '$quantEstoque', ncm = '$ncm', cfop = '$cfop', idMarca = '$idMarca', idCategoria = '$idCategoria', unidMedida = '$unidMeidida'
    WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    echo "Registro alterado com sucesso";
    header("Location: produto-listar.php");
}

$sql = "SELECT * FROM produto WHERE codigo = " . $_GET['codigo'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar</title>

    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-box"></i> Alterar Produto</h1>
            <p>Modifique os dados abaixo para Alterar um novo produto</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="nome" class="form-label">Nome do Produto*</label>
                <input name="nome" type="text" class="form-control" id="nome" value="<?= $linha['nome'] ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="precoUnitarioDaCompra" class="form-label">Preço Unitário da Compra</label>
                    <input name="precoUnitarioDaCompra" type="number" class="form-control" id="precoUnitarioDaCompra"
                        value="<?= $linha['precoUnitarioDaCompra'] ?>">
                </div>

                <div class="form-group">
                    <label for="precoUnitarioDaVenda" class="form-label">Preço Unitário da Venda</label>
                    <input name="precoUnitarioDaVenda" type="number" class="form-control" id="precoUnitarioDaVenda"
                        value="<?= $linha['precoUnitarioDaVenda'] ?>">
                </div>
                <div class="form-group">
                    <label>Status*</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="status-ativo" name="status" value="1" <?= $linha['status'] == 1 ? 'checked' : '' ?>>
                            <label for="status-ativo">Ativo</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="status-inativo" name="status" value="0" <?= $linha['status'] == 0 ? 'checked' : '' ?>>
                            <label for="status-inativo">Inativo</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="product-category">Categoria*</label>
                    <select id="idCategoria" name="idCategoria" required>
                        <option value="">Selecione uma categoria</option>
                        <?php
                        $sql = "SELECT * FROM categoria ORDER BY nome";
                        $result = mysqli_query($conexao, $sql);

                        while ($row = mysqli_fetch_array($result)) { ?>
                            <option value="<?= $row['codigo'] ?>" <?= ($row['codigo']) == $linha['idCategoria'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product-marca">Marca*</label>
                    <select id="idMarca" name="idMarca" required>
                        <option value="">Selecione uma Marca</option>
                        <?php
                        $sql = "SELECT * FROM marca ORDER BY codigo";
                        $result = mysqli_query($conexao, $sql);

                        while ($row = mysqli_fetch_array($result)) { ?>
                            <option value="<?= $row['codigo'] ?>" <?= ($row['codigo']) == $linha['idMarca'] ? 'selected' : '' ?>><?= $row['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantEstoque">Unidade Comercial*</label>
                    <select name="unidMedida" id="unidMedida" required>
                        <option value="">Seleciona uma Unidade</option>
                        <option value="UNID" <?= ($linha['unidMedida'] == 'UNID') ? 'selected' : '' ?>>UNIDADE</option>
                        <option value="CX" <?= ($linha['unidMedida'] == 'CX') ? 'selected' : '' ?>>Caixa</option>
                        <option value="CENTO" <?= ($linha['unidMedida'] == 'CENTO') ? 'selected' : '' ?>>CENTO</option>
                        <option value="CJ" <?= ($linha['unidMedida'] == 'CJ') ? 'selected' : '' ?>>CONJUNTO</option>
                        <option value="CM" <?= ($linha['unidMedida'] == 'CM') ? 'selected' : '' ?>>CENTIMETRO</option>
                        <option value="CM2" <?= ($linha['unidMedida'] == 'CM2') ? 'selected' : '' ?>>CENTIMETRO QUADRADO</option>
                        <option value="JOGO" <?= ($linha['unidMedida'] == 'JOGO') ? 'selected' : '' ?>>JOGO</option>
                        <option value="KG" <?= ($linha['unidMedida'] == 'KG') ? 'selected' : '' ?>>QUILOGRAMA</option>
                        <option value="M" <?= ($linha['unidMedida'] == 'M') ? 'selected' : '' ?>>METRO</option>
                        <option value="M2" <?= ($linha['unidMedida'] == 'M2') ? 'selected' : '' ?>>METRO QUADRADO</option>
                        <option value="M3" <?= ($linha['unidMedida'] == 'M3') ? 'selected' : '' ?>>METRO CÚBICO</option>
                        <option value="MILHEI" <?= ($linha['unidMedida'] == 'MILHEI') ? 'selected' : '' ?>>MILHEIRO</option>
                        <option value="PC" <?= ($linha['unidMedida'] == 'PC') ? 'selected' : '' ?>>PEÇA</option>
                        <option value="TON" <?= ($linha['unidMedida'] == 'TON') ? 'selected' : '' ?>>TONELADA</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ncm" class="form-label">NCM</label>
                    <input name="ncm" type="number" class="form-control" id="ncm" value="<?= $linha['ncm'] ?>">
                </div>
                <div class="form-group">
                    <label for="cfop" class="form-label">CFOP</label>
                    <input name="cfop" type="number" class="form-control" id="cfop" value="<?= $linha['cfop'] ?>">
                </div>
                <div class="form-group">
                    <label for="quantEstoque" class="form-label">Quantidade em Estoque</label>
                    <input name="quantEstoque" type="number" class="form-control" id="quantEstoque"
                        value="<?= $linha['quantEstoque'] ?>">
                </div>
            </div>
           <div class="button-group">
                <a href="produto-listar.php"><button type="button" class="btn btn-secondary">Cancelar</button></a>
                <button name="salvar" type="submit" class="btn">Salvar Alterações</button>
            </div>
        </form>

    </div>
</body>

</html>