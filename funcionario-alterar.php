<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {

   
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $tipoDeAcesso = $_POST['acess'];
    $dtAdmissao = $_POST['dtAdmissao'];
    $dtDemissao = $_POST['dtDemissao'];
    $senha = $_POST['senha'];


    $sql = "INSERT INTO funcionario (nome, email, senha, telefone, cpf, endereco, logradouro, cep, bairro, cidade, uf, tipoDeAcesso,dtAdmissao, dtDemissao)
    VALUES('$nome', '$email', '$senha', '$telefone', '$cpf', '$endereco',
    '$logradouro', '$cep', '$bairro', '$cidade', '$uf', '$tipoDeAcesso', '$dtAdmissao', '$dtDemissao')";
    mysqli_query($conexao, $sql);
    echo "Registro alterado com sucesso";
}

$sql = "SELECT * FROM cliente WHERE id = " . $_GET['id'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar</title>

    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
</head>

<body>
    <?php require_once("menu.php"); ?>
    <div class="container">
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Alteração de Cliente</h5>
            </div>
        </div>

        <form method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input name="nome" type="text" class="form-control" id="nome" value="<?= $linha['nome'] ?>">
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input name="cpf" type="text" class="form-control" id="cpf" value="<?= $linha['cpf'] ?>">
            </div>
            
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input name="cpf" type="text" class="form-control" id="cpf" value="<?= $linha['cpf'] ?>">
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input name="telefone" type="text" class="form-control" id="telefone" value="<?= $linha['telefone'] ?>">
            </div>
            
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input name="endereco" type="text" class="form-control" id="endereco" value="<?= $linha['endereco'] ?>">
            </div>
             <div class="mb-3">
                <label for="tel" class="form-label">Logradouro</label>
                <input name="logradouro" type="text" class="form-control" id="logradouro" value="<?= $linha['logradouro'] ?>">
            </div>
            <div class="mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input name="cep" type="text" class="form-control" id="cep" value="<?= $linha['cep'] ?>">
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input name="cidade" type="text" class="form-control" id="cidade" value="<?= $linha['cidade'] ?>">
            </div>
             <div class="mb-3">
                <label for="bairro" class="form-label">Bairro</label>
                <input name="bairro" type="text" class="form-control" id="bairro" value="<?= $linha['bairro'] ?>">
            </div>
            <div class="mb-3">
                <label for="uf" class="form-label">UF</label>
                <select id="uf" name="uf" required >
                        <option value="">Selecione</option>
                        <option value="AC" <?= ($linha['uf'] == 'AC') ? 'selected' : '' ?>>Acre</option>
                        <option value="AL" <?= ($linha['uf'] == 'AL') ? 'selected' : '' ?>>Alagoas</option>
                        <option value="AP" <?= ($linha['uf'] == 'AP') ? 'selected' : '' ?>>Amapá</option>
                        <option value="AM" <?= ($linha['uf'] == 'AM') ? 'selected' : '' ?>>Amazonas</option>
                        <option value="BA" <?= ($linha['uf'] == 'BA') ? 'selected' : '' ?>>Bahia</option>
                        <option value="CE" <?= ($linha['uf'] == 'CE') ? 'selected' : '' ?>>Ceará</option>
                        <option value="DF" <?= ($linha['uf'] == 'DF') ? 'selected' : '' ?>>Distrito Federal</option>
                        <option value="ES" <?= ($linha['uf'] == 'ES') ? 'selected' : '' ?>>Espírito Santo</option>
                        <option value="GO" <?= ($linha['uf'] == 'GO') ? 'selected' : '' ?>>Goiás</option>
                        <option value="MA" <?= ($linha['uf'] == 'MA') ? 'selected' : '' ?>>Maranhão</option>
                        <option value="MT" <?= ($linha['uf'] == 'MT') ? 'selected' : '' ?>>Mato Grosso</option>
                        <option value="MS" <?= ($linha['uf'] == 'MS') ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                        <option value="MG" <?= ($linha['uf'] == 'MG') ? 'selected' : '' ?>>Minas Gerais</option>
                        <option value="PA" <?= ($linha['uf'] == 'PA') ? 'selected' : '' ?>>Pará</option>
                        <option value="PB" <?= ($linha['uf'] == 'PB') ? 'selected' : '' ?>>Paraíba</option>
                        <option value="PR" <?= ($linha['uf'] == 'PR') ? 'selected' : '' ?>>Paraná</option>
                        <option value="PE" <?= ($linha['uf'] == 'PE') ? 'selected' : '' ?>>Pernambuco</option>
                        <option value="PI" <?= ($linha['uf'] == 'PI') ? 'selected' : '' ?>>Piauí</option>
                        <option value="RJ" <?= ($linha['uf'] == 'RJ') ? 'selected' : '' ?>>Rio de Janeiro</option>
                        <option value="RN" <?= ($linha['uf'] == 'RN') ? 'selected' : '' ?>>Rio Grande do Norte</option>
                        <option value="RS" <?= ($linha['uf'] == 'RS') ? 'selected' : '' ?>>Rio Grande do Sul</option>
                        <option value="RO" <?= ($linha['uf'] == 'RO') ? 'selected' : '' ?>>Rondônia</option>
                        <option value="RR" <?= ($linha['uf'] == 'RR') ? 'selected' : '' ?>>Roraima</option>
                        <option value="SC" <?= ($linha['uf'] == 'SC') ? 'selected' : '' ?>>Santa Catarina</option>
                        <option value="SP" <?= ($linha['uf'] == 'SP') ? 'selected' : '' ?>>São Paulo</option>
                        <option value="SE" <?= ($linha['uf'] == 'SE') ? 'selected' : '' ?>>Sergipe</option>
                        <option value="TO" <?= ($linha['uf'] == 'TO') ? 'selected' : '' ?>>Tocantins</option>
                    </select>
            </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input name="email" type="email" class="form-control" id="email" value="<?= $linha['email'] ?>">
            </div>

             <div class="mb-3">
                <label for="tipoDeAcesso" class="form-label">Tipo De Acesso</label>
                <input name="tipoDeAcesso" type="text" class="form-control" id="tipoDeAcesso" value="<?= $linha['tipoDeAcesso'] ?>">
            </div>

            <div class="mb-3">
                <label for="dtAdmissao" class="form-label">Data de Admissão</label>
                <input name="dtAdmissao" type="date" class="form-control" id="dtAdmissao" value="<?= $linha['dtAdmissao'] ?>">

                <label for="dtDemissao" class="form-label">Data de Demissão</label>
                <input name="dtDemissao" type="date" class="form-control" id="dtDemissao" value="<?= $linha['dtDemissao'] ?>">

                <label for="senha" class="form-label">Senha</label>
                <input name="senha" type="password" class="form-control" id="senha" value="<?= $linha['senha'] ?>">

            <button name="salvar" type="submit" class="btn btn-primary">Salvar</button>
            <a type="button" class="btn btn-secondary" href="cliente-listar.php">Voltar</a>
        </form>

    </div>
</body>
</html>