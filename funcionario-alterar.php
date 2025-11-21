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
    $tipoDeAcesso = $_POST['tipoDeAcesso'];
    $dtAdmissao = $_POST['dtAdmissao'];
    $senha = $_POST['senha'];
    $dtDemissao = "";
    $status = "";
    $campoStatus = "";
    if ($_POST['dtDemissao'] != '') {
        $dtDemissao = ", dtDemissao = '" . $_POST['dtDemissao'] . "'";
        $status = 0 ;
        $campoStatus = ", status = " . $status . "";  
    }


    $sql = " UPDATE funcionario SET nome = '$nome', email ='$email', senha ='$senha', telefone ='$telefone', cpf='$cpf', endereco='$endereco',
    logradouro ='$logradouro', cep ='$cep', bairro='$bairro', cidade ='$cidade', uf ='$uf', tipoDeAcesso ='$tipoDeAcesso', dtAdmissao ='$dtAdmissao' $campoStatus $dtDemissao WHERE codigo = " . $_GET['codigo'];
    mysqli_query($conexao, $sql);
    echo "Registro alterado com sucesso";
    header("Location: funcionario-listar.php");
}

$sql = "SELECT * FROM funcionario WHERE codigo = " . $_GET['codigo'];
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
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-user"></i> Alteração de Funcionário</h1>
            <p>Preencha os dados abaixo para alterar um novo funcionário</p>
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
                    <label for="cpf" class="form-label">CPF*</label>
                    <input name="cpf" type="text" class="form-control" id="cpf" value="<?= $linha['cpf'] ?>">
                </div>
                <div class="form-group">
                    <label for="telefone" class="form-label">Telefone*</label>
                    <input name="telefone" type="text" class="form-control" id="telefone"
                        value="<?= $linha['telefone'] ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email" class="form-label">Email*</label>
                    <input name="email" type="email" class="form-control" id="email" value="<?= $linha['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="senha" class="form-label">Senha*</label>
                    <input name="senha" type="password" class="form-control" id="senha" value="<?= $linha['senha'] ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cep" class="form-label">CEP*</label>
                    <input name="cep" type="text" class="form-control" id="cep" value="<?= $linha['cep'] ?>">
                </div>

                <div class="form-group">
                    <label for="uf" class="form-label">UF*</label>
                    <select id="uf" name="uf" required>
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

            <div class="form-row">
                <div class="form-group">
                    <label for="cidade" class="form-label">Cidade*</label>
                    <input name="cidade" type="text" class="form-control" id="cidade" value="<?= $linha['cidade'] ?>">
                </div>

                <div class="form-group">
                    <label for="tel" class="form-label">Logradouro*</label>
                    <input name="logradouro" type="text" class="form-control" id="logradouro"
                        value="<?= $linha['logradouro'] ?>">
                </div>

                <div class="form-group">
                    <label for="endereco" class="form-label">Endereço*</label>
                    <input name="endereco" type="text" class="form-control" id="endereco"
                        value="<?= $linha['endereco'] ?>">
                </div>
                <div class="form-group">
                    <label for="bairro" class="form-label">Bairro*</label>
                    <input name="bairro" type="text" class="form-control" id="bairro" value="<?= $linha['bairro'] ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tipo de Acesso*</label>
                    <div class="radio-option">
                        <input type="radio" id="acess1" name="tipoDeAcesso" value="1" <?= ($linha['tipoDeAcesso'] == '1') ? 'checked' : '' ?>>
                        <label for="acess1">Funcionário</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="acess0" name="tipoDeAcesso" value="0" <?= ($linha['tipoDeAcesso'] == '0') ? 'checked' : '' ?>>
                        <label for="acess0">Gerente</label>
                    </div>
                </div>

            <div class="form-group">
                <label for="dtAdmissao" class="form-label">Data de Admissão*</label>
                <input name="dtAdmissao" type="date" class="form-control" id="dtAdmissao"
                    value="<?= $linha['dtAdmissao'] ?>">
            </div>
            <div class="form-group">
                <label for="dtDemissao" class="form-label">Data de Demissão</label>
                <input name="dtDemissao" type="date" class="form-control" id="dtDemissao"
                    value="<?= $linha['dtDemissao'] ?>">
            </div>
            </div>

            <div class="button-group">
                <a href="funcionario-listar.php"><button type="button" class="btn btn-secondary">Cancelar</button></a>
                <button name="salvar" type="submit" class="btn">Salvar Alterações</button>
            </div>
        </form>

    </div>
</body>

</html>