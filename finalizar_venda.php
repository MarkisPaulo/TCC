<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os itens do carrinho da sessão
    $itens = $_SESSION['carrinho'];
    
    // Valida se há itens
    if (empty($itens)) {
        header("Location: venda.php?erro=Carrinho vazio");
        exit;
    }
    
    // DADOS DO PAGAMENTO
    $idCliente = (int)$_POST['idCliente'];
    $formaRecebimento = $_POST['formaRecebimento'];
    $valorPago = floatval(str_replace(',', '.', preg_replace('/[^0-9,]/', '', $_POST['valorPago'])));
    $observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : '';

    // Calcula o valor total
    $valorTotal = 0;
    foreach ($itens as $item) {
        $valorTotal += $item['precoUnitario'] * $item['quantidade'];
    }

    $idFuncionario = $_SESSION['codigo'];
    $dataHora = date('Y-m-d H:i:s');
    
    // 1. Insere a VENDA
    $sqlVenda = "INSERT INTO vendas (valorTotal, formaDeRecebimento, observacoes, idFuncionario, idCliente) 
                 VALUES ($valorTotal, '$formaRecebimento', '$observacoes', $idFuncionario, $idCliente)";
    
    if (!mysqli_query($conexao, $sqlVenda)) {
        die("Erro ao salvar venda: " . mysqli_error($conexao));
    }
    
    $numeroDaVenda = mysqli_insert_id($conexao);

    // 2. Insere itens da venda
    foreach ($itens as $item) {
        $codigoProduto = (int)$item['codigo'];
        $quantidade = (int)$item['quantidade'];
        $precoUnitario = (float)$item['precoUnitario'];

        $sqlItem = "INSERT INTO vendahasproduto (FkNumeroDaVenda, FkCodigoProduto, quantidade, precoUnitDaVenda) 
                    VALUES ($numeroDaVenda, $codigoProduto, $quantidade, $precoUnitario)";

        if (!mysqli_query($conexao, $sqlItem)) {
            die("Erro ao salvar item: " . mysqli_error($conexao));
        }

        // Atualiza o estoque
        $sqlUpdateEstoque = "UPDATE produto 
                             SET quantEstoque = quantEstoque - $quantidade 
                             WHERE codigo = $codigoProduto";

        if (!mysqli_query($conexao, $sqlUpdateEstoque)) {
            die("Erro ao atualizar estoque: " . mysqli_error($conexao));
        }
    }

    // 3. REGISTRA O RECEBIMENTO
    $valorRecebido = $valorPago;
    $valorReceber = $valorTotal - $valorPago;
    $statusRecebimento = ($valorReceber <= 0) ? 1 : 0; // 1 = pago, 0 = prazo
    
    $dataVencimento = date('Y-m-d', strtotime("+30 days"));
    $dataRecebimento = $statusRecebimento == 1 ? date('d-m-Y') : '00-00-0000';
    
    $sqlRecebimento = "INSERT INTO recebimentos 
        (formaDeRecebimento, valorRecebido, valorReceber, dataVencimento, dataRecebimento, status, idVenda) 
        VALUES ('$formaRecebimento', $valorRecebido, $valorReceber, '$dataVencimento', '$dataRecebimento', $statusRecebimento, $numeroDaVenda)";
    
    if (!mysqli_query($conexao, $sqlRecebimento)) {
        die("Erro ao registrar recebimento: " . mysqli_error($conexao));
    }

    // 4. LIMPA O CARRINHO E CLIENTE DA SESSÃO
    unset($_SESSION['carrinho']);
    unset($_SESSION['cliente_venda']);
    $_SESSION['carrinho'] = array(); // Reinicia como array vazio

    header("Location: venda.php?sucesso=1&numero=$numeroDaVenda");
    exit;
} 
?>