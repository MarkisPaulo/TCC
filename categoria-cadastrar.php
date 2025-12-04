<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
require_once("notificacoes.php");
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];

    $sql = "INSERT INTO categoria (nome) VALUES('$nome')";

    mysqli_query($conexao, $sql);
    setNotificacao('sucesso', 'Categoria cadastrada com sucesso!');
    header("Location: categoria-listar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/notificacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
    <title>Cadastro de Categoria</title>
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-tags"></i> Cadastro de Categoria</h1>
            <p>Preencha os dados abaixo para cadastrar uma nova categoria</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome*</label>
                <input name="nome" type="text" id="nome" placeholder="Digite o nome da Categoria" required>
            </div>


            <div class="button-group">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button name="cadastrar" type="submit" class="btn">Cadastrar Categoria</button>
            </div>
        </form>
    </div>
</body>

</html>