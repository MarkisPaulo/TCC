<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {

   
 $nome = $_POST['nome'];
   


    $sql = "INSERT INTO funcionario (nome, email, senha, telefone, cpf, endereco, logradouro, cep, bairro, cidade, uf, tipoDeAcesso,dtAdmissao, dtDemissao)
    VALUES('$nome', '$email', '$senha', '$telefone', '$cpf', '$endereco',
    '$logradouro', '$cep', '$bairro', '$cidade', '$uf', '$tipoDeAcesso', '$dtAdmissao', '$dtDemissao')";
    mysqli_query($conexao, $sql);
    echo "Registro alterado com sucesso";
}

$sql = "SELECT * FROM cliente WHERE id = " . $_GET['id'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar</title>

    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
</head>

<body>
    <?php require_once("menu.php"); ?>
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Alteração de Cliente</h5>
            </div>
        </div>

        <form method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input name="nome" type="text" class="form-control" id="nome" value="<?= $linha['nome'] ?>">
            </div>
            <div class="mb-3">
                <label for="precoUnitarioDaCompra" class="form-label">Preço Unitário da Compra</label>
                <input name="precoUnitarioDaCompra" type="number" class="form-control" id="precoUnitarioDaCompra" value="<?= $linha['precoUnitarioDaCompra'] ?>">
            </div>
            
            <div class="mb-3">
                <label for="precoUnitarioDaVenda" class="form-label">Preço Unitário da Venda</label>
                <input name="precoUnitarioDaVenda" type="number" class="form-control" id="precoUnitarioDaVenda" value="<?= $linha['precoUnitarioDaVenda'] ?>">
            </div>
            <div class="mb-3">
                <label for="quantEstoque" class="form-label">Quantidade em Estoque</label>
                <input name="quantEstoque" type="number" class="form-control" id="quantEstoque" value="<?= $linha['quantEstoque'] ?>">
            </div>
            
            <div class="mb-3">
                <label for="ncm" class="form-label">NCM</label>
                <input name="ncm" type="number" class="form-control" id="ncm" value="<?= $linha['ncm'] ?>">
            </div>
             <div class="mb-3">
                <label for="cfop" class="form-label">CFOP</label>
                <input name="cfop" type="cfop" class="form-control" id="cfop" value="<?= $linha['cfop'] ?>">
            </div>

            <button name="salvar" type="submit" class="btn btn-primary">Salvar</button>
            <a type="button" class="btn btn-secondary" href="cliente-listar.php">Voltar</a>
        </form>

    </div>
</body>
</html>