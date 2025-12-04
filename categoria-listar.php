<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
require_once("notificacoes.php");

if (isset($_GET['codigo']) && $_GET['status'] == 1) {
    $sql = "UPDATE categoria SET status = 0 WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    setNotificacao('erro', 'Categoria Inativada');
    header("Location: categoria-listar.php");
    exit;
} else if (isset($_GET["codigo"]) && $_GET["status"] == 0) {
    $sql = "UPDATE categoria SET status = 1 WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    setNotificacao('sucesso', 'Categoria Ativada com sucesso.');
    header("Location: categoria-listar.php");
    exit;
}

$textoBusca = "";
if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $textoBusca = $_GET['busca'];
    $sqlA = "SELECT * FROM categoria  
                WHERE status = 1
             AND (nome LIKE '%$textoBusca%' OR codigo LIKE '%$textoBusca%')
             ORDER BY codigo";
    $resultadoA = mysqli_query($conexao, $sqlA);

    $sqlI = "SELECT * FROM categoria  
                WHERE status = 0
             AND (nome LIKE '%$textoBusca%' OR codigo LIKE '%$textoBusca%')
             ORDER BY codigo";
    $resultadoI = mysqli_query($conexao, $sqlI);
    $quantI = [];
    while ($row = mysqli_fetch_assoc($resultadoI)) {
        $quantI[] = $row;
    }
} else {
    $sqlA = "SELECT * FROM categoria WHERE status = 1 ORDER BY codigo";
    $resultadoA = mysqli_query($conexao, $sqlA);

    $quantI = [];

    $sqlI = "SELECT * FROM categoria WHERE status = 0 ORDER BY codigo";
    $resultadoI = mysqli_query($conexao, $sqlI);
    while ($row = mysqli_fetch_assoc($resultadoI)) {
        $quantI[] = $row;
    }
}

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
    <link rel="stylesheet" href="assets/css/notificacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<body>

    <?php require_once("header.php"); ?>

    <div class="container">

        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    Listagem de Categoria
                    <a href="categoria-cadastrar.php">
                        <i class="fas fa-solid fa-circle-plus"></i> Nova Categoria
                    </a>
                </h5>
            </div>
        </div>

        <div class="search-container">
            <form method="GET">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Pesquisar por código ou nome" name="busca"
                        value="<?= $textoBusca ?>">
                    </button>
                </div>
            </form>
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
                <?php while ($linha = mysqli_fetch_assoc($resultadoA)) { ?>
                    <tr>
                        <td><?= $linha['codigo'] ?></td>
                        <td><?= ($linha['status'] == 1) ? 'Ativo' : 'Inativo' ?></td>
                        <td><?= $linha['nome'] ?></td>
                        <td class="actions">
                            <a href="categoria-alterar.php?codigo=<?= $linha['codigo'] ?>" class="btn btn-warning">
                                <i class="fas fa-solid fa-pen-to-square"></i> Alterar </a>
                            <a href="categoria-listar.php?codigo=<?= $linha['codigo'] ?>&status=<?= $linha['status'] ?>"
                                class="btn btn-danger" onclick="return confirm('Confirma Inativação?')"><i
                                    class="fas fa-solid fa-circle-xmark"></i>Inativar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if (count($quantI) > 0) { ?>
            <div class="card" style="margin-top: 20px;">
                <div class="card-body">
                    <h5 class="title">
                        Listagem de Categorias Inativadas
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
                    <?php foreach ($quantI as $linha) { ?>
                        <tr>
                            <td><?= $linha['codigo'] ?></td>
                            <td><?= ($linha['status'] == 1) ? 'Ativo' : 'Inativo' ?></td>
                            <td><?= $linha['nome'] ?></td>
                            <td class="actions">
                                <a href="categoria-listar.php?codigo=<?= $linha['codigo'] ?>&status=<?= $linha['status'] ?>"
                                    class="btn btn-ativar" onclick="return confirm('Confirma Ativação?')">
                                    <i class="fas fa-solid fa-circle-check"></i> Ativar </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</body>

</html>