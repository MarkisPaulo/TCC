<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itensJSON = $_POST['itens'];
    $itens = json_decode($itensJSON, true);
    
    // NOVOS CAMPOS DE PAGAMENTO
    $idCliente = (int)$_POST['idCliente'];
    $formaRecebimento = $_POST['formaRecebimento'];
    $valorPago = floatval(str_replace(',', '.', preg_replace('/[^0-9,]/', '', $_POST['valorPago'])));
    $observacoes = isset($_POST['observacoes']) ? $_POST['observacoes'] : '';

    $valorTotal = 0;
    foreach ($itens as $item) {
        $valorTotal += $item['precoUnitario'] * $item['quantidade'];
    }

        $idFuncionario = $_SESSION['codigo'];
        $dataHora = date('Y-m-d H:i:s');
        
        // 1. Insere a VENDA
        $sqlVenda = "INSERT INTO vendas (valorTotal, formaDeRecebimento, observacoes, idFuncionario, idCliente) 
                     VALUES ($valorTotal, '$formaRecebimento', '$observacoes', $idFuncionario, $idCliente)";
        
        mysqli_query($conexao, $sqlVenda);
        
        $numeroDaVenda = mysqli_insert_id($conexao);

        // 2. Insere itens da venda
        foreach ($itens as $item) {
            $codigoProduto = (int)$item['codigo'];
            $quantidade = (int)$item['quantidade'];
            $precoUnitario = (float)$item['precoUnitario'];

            $sqlEstoque = "SELECT quantEstoque FROM produto WHERE codigo = $codigoProduto";
            $resultEstoque = mysqli_query($conexao, $sqlEstoque);
            $produtoEstoque = mysqli_fetch_assoc($resultEstoque);

            $sqlItem = "INSERT INTO vendahasproduto (FkNumeroDaVenda, FkCodigoProduto, quantidade, precoUnitDaVenda) 
                        VALUES ($numeroDaVenda, $codigoProduto, $quantidade, $precoUnitario)";

            mysqli_query($conexao, $sqlItem);

            $sqlUpdateEstoque = "UPDATE produto 
                                 SET quantEstoque = quantEstoque - $quantidade 
                                 WHERE codigo = $codigoProduto";

            mysqli_query($conexao, $sqlUpdateEstoque);
        }

        // 3. REGISTRA O RECEBIMENTO
        $valorRecebido = $valorPago;
        $valorReceber = $valorTotal - $valorPago;
        $statusRecebimento = ($valorReceber <= 0) ? 1 : 0; // 1 = pago, 0 = prazo
        
        $dataVencimento = date('Y-m-d', strtotime("+30 days"));
        $dataRecebimento = $statusRecebimento == 1 ? date('Y-m-d') : '0000-00-00';
        
        $sqlRecebimento = "INSERT INTO recebimentos 
            (formaDeRecebimento, valorRecebido, valorReceber, dataVencimento, dataRecebimento, status, idVenda) 
            VALUES ('$formaRecebimento', $valorRecebido, $valorReceber, '$dataVencimento', '$dataRecebimento', $statusRecebimento, $numeroDaVenda)";
        
        mysqli_query($conexao, $sqlRecebimento);

        header("Location: venda.php?sucesso=1&numero=$numeroDaVenda&limpar=1");
        exit;

} 
?>