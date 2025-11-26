<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {

    $nome = $_POST['nome'];
    $status = $_POST['status'];


    $sql = "UPDATE marca SET nome = '$nome', status = '$status' WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    echo "Registro alterado com sucesso";
    header("Location: marca-listar.php");
}

$sql = "SELECT * FROM marca WHERE codigo = " . $_GET['codigo'];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-user"></i> Alteração de Marca</h1>
            <p>Preencha os dados abaixo para alterar a marca</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="POST">

        
            <div class="form-group">
                <label for="nome" class="form-label">Nome*</label>
                <input name="nome" type="text" class="form-control" id="nome" value="<?= $linha['nome'] ?>">
            </div>

          <div class="form-row">
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

            <div class="button-group">
                <a href="marca-listar.php"><button type="button" class="btn btn-secondary">Voltar</button></a>
                <button name="salvar" type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>

    </div>
    </div>
</body>
</html>