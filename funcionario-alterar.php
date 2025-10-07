<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {

   
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $tipoDeAcesso = $_POST['acess'];
    $dtAdmissao = $_POST['dtAdmissao'];
    $dtDemissao = $_POST['dtDemissao'];
    $senha = $_POST['senha'];


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
                <input name="cpf" type="text" class="form-control" id="cpf" value="<?= $linha['cpf'] ?>">
            </div>
            
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input name="cpf" type="text" class="form-control" id="cpf" value="<?= $linha['cpf'] ?>">
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input name="telefone" type="text" class="form-control" id="telefone" value="<?= $linha['telefone'] ?>">
            </div>
            
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input name="endereco" type="text" class="form-control" id="endereco" value="<?= $linha['endereco'] ?>">
            </div>
             <div class="mb-3">
                <label for="tel" class="form-label">Logradouro</label>
                <input name="logradouro" type="text" class="form-control" id="logradouro" value="<?= $linha['logradouro'] ?>">
            </div>
            <div class="mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input name="cep" type="text" class="form-control" id="cep" value="<?= $linha['cep'] ?>">
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input name="cidade" type="text" class="form-control" id="cidade" value="<?= $linha['cidade'] ?>">
            </div>
             <div class="mb-3">
                <label for="bairro" class="form-label">Bairro</label>
                <input name="bairro" type="text" class="form-control" id="bairro" value="<?= $linha['bairro'] ?>">
            </div>
            <div class="mb-3">
                <label for="uf" class="form-label">UF</label>
                <input name="uf" type="text" class="form-control" id="uf" value="<?= $linha['uf'] ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" type="email" class="form-control" id="email" value="<?= $linha['email'] ?>">
            </div>

             <div class="mb-3">
                <label for="tipoDeAcesso" class="form-label">Tipo De Acesso</label>
                <input name="tipoDeAcesso" type="text" class="form-control" id="tipoDeAcesso" value="<?= $linha['tipoDeAcesso'] ?>">
            </div>

 <div class="mb-3">
                <label for="dtAdmissao" class="form-label">Data de Admissão</label>
                <input name="dtAdmissao" type="date" class="form-control" id="dtAdmissao" value="<?= $linha['dtAdmissao'] ?>">

                <label for="dtDemissao" class="form-label">Data de Demissão</label>
                <input name="dtDemissao" type="date" class="form-control" id="dtDemissao" value="<?= $linha['dtDemissao'] ?>">

            <button name="salvar" type="submit" class="btn btn-primary">Salvar</button>
            <a type="button" class="btn btn-secondary" href="cliente-listar.php">Voltar</a>
        </form>

    </div>
</body>
</html>