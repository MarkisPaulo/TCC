<?php
require_once("conexao.php");
if (isset($_GET['codigo']) && $_GET['status'] == 1) {
    $sql = "UPDATE produto SET status = 0 WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Produto Inativado com sucesso.";
} else if (isset($_GET["codigo"]) && $_GET["status"] == 0) {
    $sql = "UPDATE produto SET status = 1 WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Produto Ativado com sucesso.";
}

$textoBusca = "";
if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $textoBusca = $_GET['busca'];
    $sql = "SELECT * FROM produto 
            WHERE status = 1
            AND (nome LIKE '%$textoBusca%' OR codigo LIKE '%$textoBusca%')
            ORDER BY codigo";
    $resultado = mysqli_query($conexao, $sql);

    $sqlI = "SELECT * FROM produto 
             WHERE status = 0
             AND (nome LIKE '%$textoBusca%' OR codigo LIKE '%$textoBusca%')
             ORDER BY codigo";
} else {
    $sql = "SELECT * FROM produto WHERE status = 1 ORDER BY codigo";
    $resultado = mysqli_query($conexao, $sql);

    $sqlI = "SELECT * FROM produto WHERE status = 0 ORDER BY codigo";
}

$quantI = [];
$resultadoI = mysqli_query($conexao, $sqlI);
while ($row = mysqli_fetch_assoc($resultadoI)) {
    $quantI[] = $row;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Produto</title>

    <script src="https://kit.fontawesome.com/836f33e838.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/listar.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
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

        <div class="search-container">
            <form method="GET">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Pesquisar por código ou nome..." name="busca" value="<?= $textoBusca ?>">
                </div>
            </form>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Status</th>
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
                while($linha = mysqli_fetch_assoc($resultado) ) { ?>
                <tr>
                    <td><?= $linha['codigo'] ?></td>
                    <td><?= $linha['nome'] ?></td>
                    <td><?= $linha['status'] == 1 ? 'Ativo' : 'Inativo' ?></td>
                    <td><?= "R$ " . number_format($linha['precoUnitarioDaCompra'], 2, ',', '.') ?></td>
                    <td><?= "R$ " . number_format($linha['precoUnitarioDaVenda'], 2, ',', '.') ?></td>
                    <td>
                        <?php
                            $sqlC = "SELECT nome FROM categoria WHERE codigo = " . $linha['idCategoria'];
                            $resultC = mysqli_query($conexao, $sqlC);
                            $rowC = mysqli_fetch_assoc($resultC);
                            echo $rowC['nome'];
                        ?>
                    </td>
                    <td>
                    <?php
                        $sqlM = "SELECT nome FROM marca WHERE codigo = " . $linha['idMarca'];
                        $resultM = mysqli_query($conexao, $sqlM);
                        $rowM = mysqli_fetch_assoc($resultM);
                        echo $rowM['nome'];
                    ?>
                    </td>
                    <td><?= $linha['quantEstoque'] ?></td>
                    <td><?= $linha['ncm'] ?></td>
                    <td><?= $linha['cfop'] ?></td>
                    <td class="actions">
                        <a href="produto-alterar.php?codigo=<?= $linha['codigo']?>" class="btn btn-warning">
                            <i class="fas fa-solid fa-pen-to-square"></i>Alterar</a>
                        <a href="produto-listar.php?codigo=<?= $linha['codigo'] ?>&status=<?= $linha['status'] ?>" class="btn btn-danger"
                            onclick="return confirm('Confirma inativação?')"><i class="fas fa-solid fa-circle-xmark"></i>Inativar</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if (count($quantI) > 0) { ?>
            <div class="card" style="margin-top: 20px;">
                <div class="card-body">
                    <h5 class="title">
                        Listagem de Produtos Inativados
                    </h5>
                </div>
            </div>

            <table class="table-container">
                <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Status</th>
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
                    <?php foreach ($quantI as $linha) { ?>
                    <tr>
                        <td><?= $linha['codigo'] ?></td>
                        <td><?= $linha['nome'] ?></td>
                        <td><?= $linha['status'] == 1 ? 'Ativo' : 'Inativo' ?></td>
                        <td><?= "R$ " . number_format($linha['precoUnitarioDaCompra'], 2, ',', '.') ?></td>
                        <td><?= "R$ " . number_format($linha['precoUnitarioDaVenda'], 2, ',', '.') ?></td>
                        <td>
                            <?php
                                $sqlC = "SELECT nome FROM categoria WHERE codigo = " . $linha['idCategoria'];
                                $resultC = mysqli_query($conexao, $sqlC);
                                $rowC = mysqli_fetch_assoc($resultC);
                                echo $rowC['nome'];
                            ?>
                        </td>
                        <td>
                        <?php
                            $sqlM = "SELECT nome FROM marca WHERE codigo = " . $linha['idMarca'];
                            $resultM = mysqli_query($conexao, $sqlM);
                            $rowM = mysqli_fetch_assoc($resultM);
                            echo $rowM['nome'];
                        ?>
                        </td>
                        <td><?= $linha['quantEstoque'] ?></td>
                        <td><?= $linha['ncm'] ?></td>
                        <td><?= $linha['cfop'] ?></td>
                        <td class="actions">
                            <a href="produto-listar.php?codigo=<?= $linha['codigo'] ?>&status=<?= $linha['status'] ?>" class="btn btn-ativar"
                                onclick="return confirm('Confirma ativação?')"><i class="fas fa-solid fa-circle-check"></i> Ativar</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

    </div>
</body>
</html>