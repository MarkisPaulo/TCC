<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
if (isset($_POST['cadastrar'])) {

    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $uf = $_POST['uf'];
    $email = $_POST['email'];


    $sql = "INSERT INTO cliente (nome, cpf, telefone, endereco, logradouro, cep, bairro, cidade, uf, email) VALUES('$nome', '$cpf', '$telefone', '$endereco', '$logradouro', '$cep', '$bairro', '$cidade', '$uf', '$email')";

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

        // CPF
        const cpfInput = document.getElementById('cpf');

        function formatCPF(value) {
            const v = value.replace(/\D/g, '').slice(0, 11);
            return v
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }

        cpfInput.addEventListener('input', function (e) {
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            e.target.value = formatCPF(oldValue);
            const diff = e.target.value.length - oldValue.length;
            const newPos = Math.max(0, cursorPos + diff);
            e.target.setSelectionRange(newPos, newPos);
        });

        cpfInput.addEventListener('blur', function () {
            const raw = cpfInput.value.replace(/\D/g, '');
            if (raw && raw.length !== 11) {
                cpfInput.value = formatCPF(raw);
            }
        });

        // Formatação para telefone: (00) 00000-0000 ou (00) 0000-0000
        const telefoneInput = document.getElementById('telefone');

        function formatTelefone(value) {
            const v = value.replace(/\D/g, '').slice(0, 11);
            if (v.length <= 10) {
                // formato antigo sem 9º dígito
                return v
                    .replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3')
                    .replace(/-$/, '');
            }
            // formato com 9º dígito
            return v
                .replace(/^(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3')
                .replace(/-$/, '');
        }

        telefoneInput.addEventListener('input', function (e) {
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            e.target.value = formatTelefone(oldValue);
            const diff = e.target.value.length - oldValue.length;
            const newPos = Math.max(0, cursorPos + diff);
            e.target.setSelectionRange(newPos, newPos);
        });

        telefoneInput.addEventListener('blur', function () {
            const raw = telefoneInput.value.replace(/\D/g, '');
            if (raw) telefoneInput.value = formatTelefone(raw);
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
            <h1><i class="fas fa-thin fa-user"></i> Cadastro de Cliente</h1>
            <p>Preencha os dados abaixo para cadastrar um novo cliente</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="post">

            <div class="form-group">
                <label for="nome">Nome Completo*</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF*</label>
                <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" required>
            </div>


            <div class="form-row">

                <div class="form-group">
                    <label for="cep">CEP*</label>
                    <input type="text" name="cep" id="cep" placeholder="00000-000" required>
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
                    <input type="tel" name="telefone" id="telefone" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="button-group">
              <a href="cliente-listar.php"><button type="button" class="btn btn-secondary">Cancelar</button></a>
                <button name="cadastrar" type="submit" class="btn">Cadastrar Cliente</button>
            </div>
        </form>
    </div>
</body>

</html>