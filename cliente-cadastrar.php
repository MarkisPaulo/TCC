<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
if (isset($_POST['cadastrar'])) {

    $cpf_cnpj = $_POST['cpf_cnpj'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $email = $_POST['email'];


    $sql = "INSERT INTO cliente (nome, cpf_cnpj, telefone, endereco, logradouro, cep, bairro, cidade, uf, email) VALUES('$nome', '$cpf_cnpj', '$telefone', '$endereco', '$logradouro', '$cep', '$bairro', '$cidade', '$uf', '$email')";

    mysqli_query($conexao, $sql);
    echo "Registro salvo com sucesso";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/masks.css">
    <link rel="stylesheet" href="assets/css/cep.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-user"></i> Cadastro de Cliente</h1>
            <p>Preencha os dados abaixo para cadastrar um novo cliente</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form  method="POST" id="form" data-validate>

            <div class="form-group">
                <label for="nome">Nome Completo*</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>
            </div>

            <div class="form-group">
                <label for="cpf_cnpj">CPF/CNPJ*</label>
                <input type="text" name="cpf_cnpj" id="cpf-cnpj" data-mask="cpf-cnpj" placeholder="000.000.000-00  00.000.000/0000-00" maxlength="18" minlength="14" required>
            </div>


            <div class="form-row">

                <div class="form-group">
                    <label for="cep">CEP*</label>
                    <input type="text" name="cep" id="cep" data-mask="cep" placeholder="00000-000" maxlength="9" required>
                </div>

                <div class="form-group">
                    <label for="uf">UF*</label>
                    <select id="uf" name="uf" required>
                        <option value="">Selecione</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade*</label>
                    <input type="text" name="cidade" id="cidade" placeholder="Nome da cidade" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="logradouro">Logradouro*</label>
                    <input type="text" name="logradouro" id="logradouro" placeholder="Rua, número, complemento"
                        required>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço Completo*</label>
                    <input type="text" name="endereco" id="endereco" placeholder="Rua/Avenida/Praça" required>
                </div>

                <div class="form-group">
                    <label for="bairro">Bairro*</label>
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro...." required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="exemplo@email.com">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone*</label>
                    <input type="tel" name="telefone" id="telefone" placeholder="(00) 00000-0000" data-mask="tel" maxlength="15" required>
                </div>
            </div>

            <div class="button-group">
              <a href="cliente-listar.php"><button type="button" class="btn btn-secondary">Cancelar</button></a>
                <button name="cadastrar" type="submit" class="btn">Cadastrar Cliente</button>
            </div>
        </form>
    </div>
    <script src="assets/js/masks.js"></script>
    <script src="assets/js/validacao.js"></script>
    <script src="assets/js/buscaCEP.js"></script>
</body>

</html>