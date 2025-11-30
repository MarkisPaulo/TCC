<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

if (isset($_POST['confirmar'])) {
    $codigo = $_POST['codigo'];
    $valorRecebido = floatval(str_replace(',', '.', preg_replace('/[^0-9,]/', '', $_POST['valorRecebido'])));
    $dataRecebimento = $_POST['dataRecebimento'];
    
    // Atualiza o recebimento
    $sql = "UPDATE recebimentos SET 
            valorRecebido = valorRecebido + $valorRecebido,
            valorReceber = valorReceber - $valorRecebido,
            dataRecebimento = '$dataRecebimento',
            status = IF(valorReceber - $valorRecebido <= 0, 1, 0)
            WHERE codigo = $codigo";
    
    if (mysqli_query($conexao, $sql)) {
        header("Location: recebimento_listar.php?mensagem=Recebimento registrado com sucesso");
        exit;
    } else {
        $erro = "Erro ao registrar recebimento: " . mysqli_error($conexao);
    }
}

$codigo = $_GET['codigo'];
$sql = "SELECT 
    r.*,
    v.numeroDaVenda,
    v.valorTotal,
    c.nome as nomeCliente,
    c.telefone
FROM recebimentos r
INNER JOIN vendas v ON r.idVenda = v.numeroDaVenda
INNER JOIN cliente c ON v.idCliente = c.codigo
WHERE r.codigo = $codigo";

$resultado = mysqli_query($conexao, $sql);
$recebimento = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Recebimento</title>
    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets/img/logoNexus.png" type="image/png">
</head>
<body>
    <?php require_once("header.php"); ?>
    
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-dollar-sign"></i> Registrar Recebimento</h1>
            <p>Confirme os dados do recebimento abaixo</p>
        </div>

        <?php if (isset($erro)) { ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <?= $erro ?>
            </div>
        <?php } ?>

        <div class="info-box">
            <p><strong>Cliente:</strong> <?= $recebimento['nomeCliente'] ?></p>
            <p><strong>Venda:</strong> #<?= $recebimento['numeroDaVenda'] ?></p>
            <p><strong>Valor Total da Venda:</strong> R$ <?= number_format($recebimento['valorTotal'], 2, ',', '.') ?></p>
            <p><strong>Valor Já Recebido:</strong> R$ <?= number_format($recebimento['valorRecebido'], 2, ',', '.') ?></p>
            <p><strong>Valor Restante:</strong> R$ <?= number_format($recebimento['valorReceber'], 2, ',', '.') ?></p>
        </div>

        <form method="POST" id="form" data-validate>
            <input type="hidden" name="codigo" value="<?= $recebimento['codigo'] ?>">
            
            <div class="form-group">
                <label>Valor a Receber*</label>
                <input type="text" name="valorRecebido" data-mask="valor" 
                       placeholder="R$ 0,00" 
                       value="R$ <?= number_format($recebimento['valorReceber'], 2, ',', '.') ?>"
                       required>
                <small style="color: #666;">Você pode receber um valor parcial</small>
            </div>

            <div class="form-group">
                <label>Data do Recebimento*</label>
                <input type="date" name="dataRecebimento" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="button-group">
                <a href="recebimento_listar.php">
                    <button type="button" class="btn btn-secondary">Cancelar</button>
                </a>
                <button type="submit" name="confirmar" class="btn">
                    <i class="fas fa-check"></i> Confirmar Recebimento
                </button>
            </div>
        </form>
    </div>

    <script src="assets/js/masks.js"></script>
</body>
</html>