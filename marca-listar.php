<?php
require_once("conexao.php");
if (isset($_GET['codigo'])) {
    $sql = "DELETE FROM marca WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Exclusão realizada com sucesso.";
}

$sql = "SELECT * FROM marca ORDER BY codigo";

$resultado = mysqli_query($conexao, $sql);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem</title>
    <script src="https://kit.fontawesome.com/836f33e838.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/listar.css">
    <link rel="stylesheet" href="assets/css/reset.css">

<body>

    <?php require_once("header.php"); ?>

    <?php if (isset($mensagem)) { ?>
        <div class="alert" role="alert">
            <?= $mensagem ?>
        </div>
    <?php } ?>

    <div class="container">

        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    Listagem de Marca
                    <a href="marca-cadastrar.php">
                        <i class="fas fa-solid fa-circle-plus"></i> Nova Marca
                    </a>
                </h5>
            </div>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Status</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($linha = mysqli_fetch_array($resultado)) { ?>
                    <tr>
                        <td><?= $linha['codigo'] ?></td>
                        <td><?= ($linha['status'] == 1) ? 'Ativo' : 'Inativo'?></td>
                        <td><?= $linha['nome'] ?></td>
                        
                        <td class="actions">
                            <a href="marca-alterar.php?codigo=<?= $linha['codigo'] ?>" class="btn btn-warning">
                                <i class="fas fa-solid fa-pen-to-square"></i> Alterar</a>
                            <a href="marca-listar.php?codigo=<?= $linha['codigo'] ?>" class="btn btn-danger"
                                onclick="return confirm('Confirma exclusão?')"><i class="fas fa-solid fa-trash-can"></i>Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>

</html>