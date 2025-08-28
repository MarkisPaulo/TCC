<?php
require_once("verificaautentaticacao.php");

?>

<?php
require_once("conexao.php");
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];
    $status = $_POST['status'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $rg = $_POST['rg'];
    $endereco = $_POST['endereco'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];



    $sql = "INSERT INTO usuario (nome, email, senha) VALUES('$nome', '$email', '$senha')";

    mysqli_query($conexao, $sql);
    echo "Registro salvo com sucesso";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>

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
                <h5 class="card-title">Cadastro de Cliente</h5>
            </div>
        </div>

        <form method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input name="nome" type="text" class="form-control" id="nome">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" type="email" class="form-control" id="email">
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input name="telefone" type="text" class="form-control" id="telefone">
            </div>

            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input name="cpf" type="text" class="form-control" id="cpf">
            </div>

            <div class="mb-3">
                <label for="rg" class="form-label">RG</label>
                <input name="rg" type="text" class="form-control" id="rg">
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input name="endereco" type="text" class="form-control" id="endereco">
            </div>

            <div class="mb-3">
                <label for="logradouro" class="form-label">logradouro</label>
                <input name="logradouro" type="text" class="form-control" id="logradouro">
            </div>

            <div class="mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input name="cep" type="text" class="form-control" id="cep">
            </div>

            <div class="mb-3">
                <label for="bairro" class="form-label">Bairro</label>
                <input name="bairro" type="text" class="form-control" id="bairro">
            </div>

            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input name="cidade" type="text" class="form-control" id="cidade">
            </div>

            <div class="mb-3">
                <label for="uf" class="form-label">UF</label>
                <input name="uf" type="text" class="form-control" id="uf">
            </div>

            <button name="cadastrar" type="submit" class="btn btn-primary">Cadastrar</button>
            <a type="button" class="btn btn-secondary" href="usuario-listar.php">Voltar</a>
        </form>

    </div>


</body>

</html>