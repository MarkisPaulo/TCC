<?php
require_once("conexao.php");
if (isset($_GET['id'])) {
    $sql = "DELETE FROM produto WHERE id = " . $_GET['id'];
    mysqli_query($conexao, $sql);
    $mensagem = "Exclusão realizada com sucesso.";
}

$sql = "SELECT * FROM produto ORDER BY id";

$resultado = mysqli_query($conexao, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

    <?php require_once("menu.php"); ?>
    <div class="container">

        <?php if (isset($mensagem)) { ?>
            <div class="alert alert-success mt-3" role="alert">
                <?= $mensagem ?>
            </div>
        <?php } ?>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">
                    Listagem de Produtos
                    <a href="produto-cadastrar.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i></a>
                </h5>
            </div>
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($linha = mysqli_fetch_array($resultado)) { ?>
                <tr>
                    <td><?= $linha['id'] ?></td>
                    <td><?= $linha['nome'] ?></td>
                    <td><?= $linha['unimedida'] ?></td>
                    <td><?= $linha['preco'] ?></td>
                    <td>
                        <a href="produto-alterar.php?id=<?= $linha['id']?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                        <a href="produto-listar.php?id=<?= $linha['id'] ?>" class="btn btn-danger" onclick="return confirm('Confirma exclusão?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>
</html>