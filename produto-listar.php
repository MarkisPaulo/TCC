<?php
require_once("conexao.php");
if (isset($_GET['codigo'])) {
    $sql = "DELETE FROM produto WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Exclusão realizada com sucesso.";
}

$sql = "SELECT * FROM produto ORDER BY codigo";
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
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php require_once("header.php"); ?>
    
    <?php if (isset($mensagem)) { ?>
            <div class="alert alert-success mt-3" role="alert">
                <?= $mensagem ?>
            </div>
        <?php } ?>

    <div class="container">

        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    Listagem de Produto
                    <a href="produto-cadastrar.php">
                        <i class="fas fa-solid fa-circle-plus"></i> Novo Produto
                    </a>
                </h5>
            </div>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Ativo</th>
                    <th scope="col">Preço Unit da Compra</th>
                    <th scope="col">Preço Unit da Venda</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Quantidade em Estoque</th>
                    <th scope="col">NCM</th>
                    <th scope="col">CFOP</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($linha = mysqli_fetch_array($resultado) ) { ?>
                <tr>
                    <td><?= $linha['codigo'] ?></td>
                    <td><?= $linha['nome'] ?></td>
                    <td><?= $linha['status'] ?></td>
                    <td><?= $linha['precoUnitarioDaCompra'] ?></td>
                    <td><?= $linha['precoUnitarioDaVenda'] ?></td>
                    <td><?php
                    $sqlC = "SELECT nome FROM categoria WHERE codigo = " . $linha['idCategoria'];
                    $resultC = mysqli_query($conexao, $sqlC);
                    $rowC = mysqli_fetch_array($resultC);
                    echo $rowC['nome'];
                    ?></td>
                    <td><?php 
                    $sqlM = "SELECT nome FROM marca WHERE codigo = " . $linha['idMarca'];
                    $resultM = mysqli_query($conexao, $sqlM);
                    $rowM = mysqli_fetch_array($resultM); ?></td>
                    <td><?= $linha['quantEstoque'] ?></td>
                    <td><?= $linha['ncm'] ?></td>
                    <td><?= $linha['cfop'] ?></td>
                    <td class="actions">
                        <a href="produto-alterar.php?codigo=<?= $linha['codigo']?>" class="btn btn-warning">
                            <i class="fas fa-solid fa-pen-to-square"></i>Alterar</a>
                        <a href="produto-listar.php?codigo=<?= $linha['codigo'] ?>" class="btn btn-danger"
                            onclick="return confirm('Confirma exclusão?')"><i class="fas fa-solid fa-trash-can"></i>Excluir</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>
</html>