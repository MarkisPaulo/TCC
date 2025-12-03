<?php
require_once("conexao.php");
if (isset($_GET['codigo']) && $_GET['status'] == 1) {
    $sql = "UPDATE funcionario SET status = 0 WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Funcionário Inativado com sucesso.";
} else if (isset($_GET["codigo"]) && $_GET["status"] == 0) {
    $sql = "UPDATE funcionario SET status = 1 WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Funcionário Ativado com sucesso.";
}

$textoBusca = "";
if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $textoBusca = $_GET['busca'];
    $sql = "SELECT * FROM funcionario 
            WHERE status = 1
            AND (nome LIKE '%$textoBusca%' OR codigo LIKE '%$textoBusca%' OR cpf LIKE '%$textoBusca%')
            ORDER BY codigo";
    $resultado = mysqli_query($conexao, $sql);

    $sqlI = "SELECT * FROM funcionario 
             WHERE status = 0
             AND (nome LIKE '%$textoBusca%' OR codigo LIKE '%$textoBusca%' OR cpf LIKE '%$textoBusca%')
             ORDER BY codigo";
} else {
    $sql = "SELECT * FROM funcionario WHERE status = 1 ORDER BY codigo";
    $resultado = mysqli_query($conexao, $sql);

    $sqlI = "SELECT * FROM funcionario WHERE status = 0 ORDER BY codigo";
}

$resultadoI = mysqli_query($conexao, $sqlI);
$quantI = [];
while ($row = mysqli_fetch_assoc($resultadoI)) {
    $quantI[] = $row;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Funcionário</title>

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

    <div class="containerFun">

        <div class="card">
            <div class="card-body">
                <h5 class="title">
                    Listagem de Funcionário
                    <a href="funcionario-cadastrar.php">
                        <i class="fas fa-solid fa-circle-plus"></i> Novo Funcionário
                    </a>
                </h5>
            </div>
        </div>

        <div class="search-container">
            <form method="GET">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Pesquisar por código, nome ou CPF" name="busca" value="<?= $textoBusca ?>">
                </div>
            </form>
        </div>

        <table class="table-container">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Status</th>
                    <th scope="col">Nome</th>
                    <th scope="col">CPF</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Endereço</th>
                    <th scope="col">Logradouro</th>
                    <th scope="col">CEP</th>
                    <th scope="col">Cidade</th>
                    <th scope="col">UF</th>
                    <th scope="col">Email</th>
                    <th scope="col">Acesso</th>
                    <th scope="col">Data Admissão</th>
                    <th scope="col">Data Demissão</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($linha = mysqli_fetch_array($resultado)) { ?>
                <tr>
                    <td><?= $linha['codigo'] ?></td>
                    <td><?= ($linha['status'] == 1) ? 'Ativo' : 'Inativo'?></td>
                    <td><?= $linha['nome'] ?></td>
                    <td><?= $linha['cpf'] ?></td>
                    <td><?= $linha['telefone'] ?></td>
                    <td><?= $linha['endereco'] ?></td>
                    <td><?= $linha['logradouro'] ?></td>
                    <td><?= $linha['cep'] ?></td>
                    <td><?= $linha['cidade']?></td>
                    <td><?= $linha['uf'] ?></td>
                    <td><?= $linha['email'] ?></td>
                    <td><?= ($linha['tipoDeAcesso'] == 1) ? 'Funcionário' : 'Gerente'?></td>
                    <td><?= $linha['dtAdmissao'] ?></td>
                    <td><?= $linha['dtDemissao'] ?></td>
                    <td class="actions">
                        <a href="funcionario-alterar.php?codigo=<?= $linha['codigo']?>" class="btn btn-warning">
                            <i class="fas fa-solid fa-pen-to-square"></i>Alterar</a>
                        <a href="funcionario-listar.php?codigo=<?= $linha['codigo'] ?>&status=<?= $linha['status'] ?>" class="btn btn-danger"
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
                        Listagem de Funcionários Inativados
                    </h5>
                </div>
            </div>

            <table class="table-container">
                <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Status</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Logradouro</th>
                        <th scope="col">CEP</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">UF</th>
                        <th scope="col">Email</th>
                        <th scope="col">Acesso</th>
                        <th scope="col">Data Admissão</th>
                        <th scope="col">Data Demissão</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quantI as $linha) { ?>
                    <tr>
                        <td><?= $linha['codigo'] ?></td>
                        <td><?= ($linha['status'] == 1) ? 'Ativo' : 'Inativo'?></td>
                        <td><?= $linha['nome'] ?></td>
                        <td><?= $linha['cpf'] ?></td>
                        <td><?= $linha['telefone'] ?></td>
                        <td><?= $linha['endereco'] ?></td>
                        <td><?= $linha['logradouro'] ?></td>
                        <td><?= $linha['cep'] ?></td>
                        <td><?= $linha['cidade']?></td>
                        <td><?= $linha['uf'] ?></td>
                        <td><?= $linha['email'] ?></td>
                        <td><?= ($linha['tipoDeAcesso'] == 1) ? 'Funcionário' : 'Gerente'?></td>
                        <td><?= $linha['dtAdmissao'] ?></td>
                        <td><?= $linha['dtDemissao'] ?></td>
                        <td class="actions">
                            <a href="funcionario-listar.php?codigo=<?= $linha['codigo'] ?>&status=<?= $linha['status'] ?>" class="btn btn-ativar"
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