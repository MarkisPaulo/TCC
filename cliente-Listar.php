<?php
require_once("conexao.php");
if (isset($_GET['codigo'])) {
    $sql = "DELETE FROM cliente WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    $mensagem = "Exclusão realizada com sucesso.";
}

$sql = "SELECT * FROM cliente ORDER BY codigo";

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
                    Listagem de Clientes
                    <a href="cliente-cadastrar.php">
                        <i class="fas fa-solid fa-circle-plus"></i> Novo Cliente
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
                    <th scope="col">CPF</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Endereço</th>
                    <th scope="col">Logradouro</th>
                    <th scope="col">CEP</th>
                    <th scope="col">Cidade</th>
                    <th scope="col">UF</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($linha = mysqli_fetch_array($resultado)) { ?>
                    <tr>
                        <td><?= $linha['codigo'] ?></td>
                        <td><?= ($linha['status'] == 1) ? 'Ativo' : 'Inativo'?></td>
                        <td><?= $linha['nome'] ?></td>
                        <td><?= $linha['cpf'] ?></td>
                        <td><?= $linha['telefone'] ?></td>
                        <td><?= $linha['endereco'] ?></td>
                        <td><?= $linha['logradouro'] ?></td>
                        <td><?= $linha['cep'] ?></td>
                        <td><?= $linha['cidade'] ?></td>
                        <td><?= $linha['uf'] ?></td>
                        <td><?= $linha['email'] ?></td>
                        <td class="actions">
                            <a href="cliente-alterar.php?id=<?= $linha['codigo'] ?>" class="btn btn-warning">
                                <i class="fas fa-solid fa-pen-to-square"></i> Alterar</a>
                            <a href="cliente-Listar.php?id=<?= $linha['codigo'] ?>" class="btn btn-danger"
                                onclick="return confirm('Confirma exclusão?')"><i class="fas fa-solid fa-trash-can"></i>Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>

</html>