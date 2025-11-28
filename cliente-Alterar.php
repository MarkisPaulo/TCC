<?php
require_once("conexao.php");
if (isset($_POST['salvar'])) {

    $nome = $_POST['nome'];
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
    $status = $_POST['status'];

    $sql = "UPDATE cliente SET nome = '$nome', status ='$status', cpf_cnpj = '$cpf_cnpj', telefone = '$telefone', logradouro = '$logradouro', endereco = '$endereco', cidade = '$cidade', cep = '$cep',uf = '$uf', bairro = '$bairro', email = '$email' 
    WHERE codigo = " . $_GET['codigo'];

    mysqli_query($conexao, $sql);
    echo "Registro alterado com sucesso";
    header("Location: cliente-listar.php");
}

$sql = "SELECT * FROM cliente WHERE codigo = " . $_GET['codigo'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cepInput = document.getElementById('cep');
        const logradouroInput = document.getElementById('logradouro');
        const bairroInput = document.getElementById('bairro');
        const cidadeInput = document.getElementById('cidade');
        const ufSelect = document.getElementById('uf');
        const enderecoInput = document.getElementById('endereco');

        function limpaCampos() {
            logradouroInput.value = '';
            bairroInput.value = '';
            cidadeInput.value = '';
            ufSelect.value = '';
            enderecoInput.value = '';
        }

        function formatCEP(v) {
            const d = v.replace(/\D/g, '').slice(0, 8);
            return d.length > 5 ? d.slice(0, 5) + '-' + d.slice(5) : d;
        }

        cepInput.addEventListener('input', function (e) {
            e.target.value = formatCEP(e.target.value);
        });

        cepInput.addEventListener('blur', function () {
            const cep = cepInput.value.replace(/\D/g, '');
            if (cep.length !== 8) {
                limpaCampos();
                return;
            }

            // indica carregamento
            logradouroInput.value = '...';
            bairroInput.value = '...';
            cidadeInput.value = '...';
            ufSelect.value = '';
            enderecoInput.value = '...';

            fetch('https://viacep.com.br/ws/' + cep + '/json/')
                .then(res => res.json())
                .then(data => {
                    if (data.erro) {
                        limpaCampos();
                        alert('CEP não encontrado.');
                        return;
                    }
                    logradouroInput.value = data.logradouro || '';
                    bairroInput.value = data.bairro || '';
                    cidadeInput.value = data.localidade || '';

                    // normaliza UF e seleciona somente se existir a opção
                    const ufFromApi = (data.uf || '').toUpperCase();
                    if (Array.from(ufSelect.options).some(opt => opt.value === ufFromApi)) {
                        ufSelect.value = ufFromApi;
                    } else {
                        ufSelect.value = '';
                    }

                    enderecoInput.value = data.logradouro ? data.logradouro + (data.complemento ? ', ' + data.complemento : '') : '';
                })
                .catch(err => {
                    console.error(err);
                    limpaCampos();
                    alert('Erro ao consultar CEP. Tente novamente.');
                });
        });
    });
</script>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-thin fa-user"></i> Alteração de Cliente</h1>
            <p>Preencha os dados abaixo para cadastrar um novo cliente</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="POST"  id="form" data-validate>

            <div class="form-group">
                <label for="nome">Nome Completo*</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome completo"
                    value="<?= $linha['nome'] ?>" required>
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="cpf">CPF/CNPJ*</label>
                    <input type="text" name="cpf_cnpj" name="cpf_cnpj" id="cpf_cnpj" placeholder="000.000.000-00" value="<?= $linha['cpf_cnpj'] ?>"
                        required>
                </div>

                <div class="form-group">
                    <label>Status*</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="status-ativo" name="status" value="1" <?= $linha['status'] == 1 ? 'checked' : '' ?>>
                            <label for="status-ativo">Ativo</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="status-inativo" name="status" value="0" <?= $linha['status'] == 0 ? 'checked' : '' ?>>
                            <label for="status-inativo">Inativo</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="cep">CEP*</label>
                    <input type="text" name="cep" id="cep" data-mask="cep" placeholder="00000-000" value="<?= $linha['cep'] ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="uf">UF*</label>
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

                <div class="form-group">
                    <label for="cidade">Cidade*</label>
                    <input type="text" name="cidade" id="cidade" placeholder="Nome da cidade"
                        value="<?= $linha['cidade'] ?>" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="logradouro">Logradouro*</label>
                    <input type="text" name="logradouro" id="logradouro" placeholder="Rua, número, complemento"
                        value="<?= $linha['logradouro'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="endereco">Endereço Completo*</label>
                    <input type="text" name="endereco" id="endereco" placeholder="Rua/Avenida/Praça"
                        value="<?= $linha['endereco'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="bairro">Bairro*</label>
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro...."
                        value="<?= $linha['bairro'] ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" placeholder="exemplo@email.com"
                        value="<?= $linha['email'] ?>">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone*</label>
                    <input type="tel" name="telefone" data-mask="tel" id="telefone" placeholder="(00) 00000-0000"
                        value="<?= $linha['telefone'] ?>" required>
                </div>
            </div>

            <div class="button-group">
                <a href="cliente-listar.php"><button type="button" class="btn btn-secondary">Cancelar</button></a>
                <button name="salvar" type="submit" class="btn">Salvar Alterações</button>
            </div>
        </form>
    </div>
    <script src="assets/js/masks.js"></script>
</body>

</html>