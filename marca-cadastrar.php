<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
require_once("notificacoes.php");
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];

    $sql = "INSERT INTO marca (nome) VALUES('$nome')";
    mysqli_query($conexao, $sql);
    setNotificacao('sucesso', 'Registro salvo com sucesso');
    header("Location: marca-listar.php");
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
    <title>Cadastro de Marca</title>
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-registered"></i> Cadastro de Marca</h1>
            <p>Preencha os dados abaixo para cadastrar um novo produto</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome*</label>
                <input  name="nome" type="text" id="nome" placeholder="Digite o nome da Marca" required>
            </div>
           

            <div class="button-group">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button name="cadastrar" type="submit" class="btn">Cadastrar Marca</button>
            </div>
        </form>
    </div>
</body>

</html>