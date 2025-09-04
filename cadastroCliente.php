<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/cadastroCli.css">
</head>

<body>
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
                <label for="client-name">Nome Completo*</label>
                <input type="text" id="client-name" placeholder="Digite o nome completo" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status do Cliente</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="status-ativo" name="product-status" value="true" checked>
                            <label for="status-ativo">Ativo</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="status-inativo" name="product-status" value="false">
                            <label for="status-inativo">Inativo</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="client-cpf">CPF*</label>
                    <input type="text" id="client-cpf" placeholder="000.000.000-00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="client-email">E-mail</label>
                <input type="email" id="client-email" placeholder="exemplo@email.com">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="client-rg">RG</label>
                    <input type="text" id="client-rg" placeholder="00.000.000-0">
                </div>

                <div class="form-group">
                    <label for="client-phone">Telefone*</label>
                    <input type="tel" id="client-phone" placeholder="(00) 00000-0000" required>
                </div>
            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="client-cep">CEP*</label>
                    <input type="text" id="client-cep" placeholder="00000-000" required>
                </div>

                <div class="form-group">
                    <label for="client-state">Estado*</label>
                    <select id="client-state" required>
                        <option value="">Selecione</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="SP">São Paulo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="client-city">Cidade*</label>
                    <input type="text" id="client-city" placeholder="Nome da cidade" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="client-logradouro">Logradouro*</label>
                    <input type="text" id="client-logradouro" placeholder="Rua, número, complemento" required>
                </div>

                <div class="form-group">
                    <label for="client-address">Endereço Completo*</label>
                    <input type="text" id="client-address" placeholder="Rua/Avenida/Praça" required>
                </div>

                <div class="form-group">
                    <label for="client-bairro">Bairro*</label>
                    <input type="text" id="client-bairro" placeholder="Bairro...." required>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn">Cadastrar Cliente</button>
            </div>
        </form>
    </div>
</body>


<?php
if ($_SERVER["REQUEST_METHOD"] == "post") {
    include('conn.php');

    $nome = $_POST['client-name'];
    $email = $_POST['client-email'];
    $cpf = $_POST['client-cpf'];
    $rg = $_POST['client-rg'];
    $telefone = $_POST['client-phone'];
    $cep = $_POST['client-cep'];
    $Cidade = $_POST['client-city'];
    $logradouro = $_POST['client-logradouro'];
    $endereco = $_POST['client-address'];
    $bairro = $_POST['client-bairro'];

    if (isset($_POST['product-status'])) {
        $status = $_POST['product-status'];
    } else {
        echo "Nenhum gênero foi selecionado.";
    }

    if (isset($_POST['client-state'])) {
        $uf = $_POST['client-state'];
    } else {
        echo "Nenhuma cor foi selecionada.";
    }

    if ($senha === $senha2) {

        $stmt = $conn->prepare("INSERT INTO livro (titulo, autor, paginas) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titulo, $autor, $paginas);

        if ($stmt->execute()) {
            echo "<div class='success-message'>✅ Livro cadastrado com sucesso!</div>";
            echo "<script>setTimeout(function(){ window.location.href = 'cadastroLivro.php'; }, 2500);</script>";
        } else {
            echo "<div class='success-message' style='background-color:#f8d7da; color:#721c24; border-color:#f5c6cb;'>Erro ao cadastrar: " . $conn->error . "</div>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Senhas não coincidem";
        header("Refresh: 2; url=cadastrousuario.php");
    }
}

?>

</html>