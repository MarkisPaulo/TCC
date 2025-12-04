<?php
require_once("conexao.php");
require_once("notificacoes.php");
require_once("verificaautenticacao.php");
if (isset($_POST['salvar'])) {

    $nome = $_POST['nome'];

    $sql = "UPDATE categoria SET nome = '$nome' WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    setNotificacao('alerta', 'Categoria alterada com sucesso!');
    header("Location: categoria-listar.php");
    exit;
}

$sql = "SELECT * FROM categoria WHERE codigo = " . $_GET['codigo'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_assoc($resultado);

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
    <link rel="stylesheet" href="assets/css/notificacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-user"></i> Alteração de Categoria</h1>
            <p>Preencha os dados abaixo para alterar a categoria</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="post">
            <div class="form-group">
                <label for="nome" class="form-label">Nome*</label>
                <input name="nome" type="text" class="form-control" id="nome" value="<?= $linha['nome'] ?>">
            </div>

          <div class="form-row">

            <div class="button-group">
                <a href="categoria-listar.php"><button type="button" class="btn btn-secondary">Cancelar</button></a>
                <button name="salvar" type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>