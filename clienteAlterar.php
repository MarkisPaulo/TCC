<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {

    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $email = $_POST['email'];

    $sql = "UPDATE cliente SET nome = '$nome', cpfcnpj = '$cpfcnpj', telefone = '$tell', endereco = '$endereco', cidade = '$cidade', estado = '$estado', observacao = '$obs' WHERE id = " . $_GET['id'];

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
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
                <label for="cpf" class="form-label">CPF</label>
                <input name="cpf/cnpj" type="text" class="form-control" id="cpf/cnpj" value="<?= $linha['cpfcnpj'] ?>">
            </div>
            <div class="mb-3">
                <label for="tell" class="form-label">Telefone</label>
                <input name="tell" type="text" class="form-control" id="tell" value="<?= $linha['telefone'] ?>">
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input name="endereco" type="text" class="form-control" id="endereco" value="<?= $linha['endereco'] ?>">
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input name="cidade" type="text" class="form-control" id="cidade" value="<?= $linha['cidade'] ?>">
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <input name="estado" type="text" class="form-control" id="estado" value="<?= $linha['estado'] ?>">
            </div>
            <div class="mb-3">
                <label for="obs" class="form-label">Observações</label>
                <input name="obs" type="text-area" class="form-control" id="obs" value="<?= $linha['observacao'] ?>">
            </div>
            <button name="salvar" type="submit" class="btn btn-primary">Salvar</button>
            <a type="button" class="btn btn-secondary" href="cliente-listar.php">Voltar</a>
        </form>

    </div>
</body>
</html>