<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $numeroDaVenda = (int)$_POST['numeroDaVenda'];
    $itensJSON = $_POST['itens'];
    $itens = json_decode($itensJSON, true);
    
    // Validações
    if (empty($itens) || !is_array($itens)) {
        header("Location: venda.php?erro=dados_invalidos");
        exit;
    }
    
    // Calcula total
    $valorTotal = 0;
    foreach ($itens as $item) {
        $totalVenda += $item['precoUnitario'] * $item['quantidade'];
    }
    
    // Inicia transação
    mysqli_begin_transaction($conexao);
    
    try {
        // 1. Insere na tabela VENDAS
        $idFuncionario = $_SESSION['id'] ?? 1; // Pega do usuário logado
        
        $sqlVenda = "INSERT INTO vendas ( dataVenda, valorTotal, idFuncionario) 
                     VALUES ('$dataVenda', $valorTotal, $idFuncionario)";
        
        if (!mysqli_query($conexao, $sqlVenda)) {
            throw new Exception("Erro ao inserir venda: " . mysqli_error($conexao));
        }
        
        // 2. Insere na tabela VENDA_HAS_PRODUTO (ou vendaHasProduto)
        foreach ($itens as $item) {
            $codigoProduto = (int)$item['codigo'];
            $quantidade = (int)$item['quantidade'];
            $precoUnitario = (float)$item['precoUnitario']; 
            
            // Verifica estoque
            $sqlEstoque = "SELECT quantEstoque FROM produto WHERE codigo = $codigoProduto";
            $resultEstoque = mysqli_query($conexao, $sqlEstoque);
            $produtoEstoque = mysqli_fetch_assoc($resultEstoque);
            
            if ($produtoEstoque['quantEstoque'] < $quantidade) {
                throw new Exception("Estoque insuficiente para o produto código $codigoProduto");
            }
            
            // Insere item da venda
            $sqlItem = "INSERT INTO vendahasProduto (numeroDaVenda, codigoProduto, quantidade, precoUnitario) 
                        VALUES ($numeroDaVenda, $codigoProduto, $quantidade, $precoUnitario)";
            
            if (!mysqli_query($conexao, $sqlItem)) {
                throw new Exception("Erro ao inserir item: " . mysqli_error($conexao));
            }
            
            // Atualiza estoque
            $sqlUpdateEstoque = "UPDATE produto 
                                 SET quantEstoque = quantEstoque - $quantidade 
                                 WHERE codigo = $codigoProduto";
            
            if (!mysqli_query($conexao, $sqlUpdateEstoque)) {
                throw new Exception("Erro ao atualizar estoque: " . mysqli_error($conexao));
            }
        }
        
        // Confirma transação
        mysqli_commit($conexao);
        
        // Redireciona com sucesso
        header("Location: venda.php?sucesso=1&numero=$numeroVenda");
        exit;
        
    } catch (Exception $e) {
        // Desfaz tudo em caso de erro
        mysqli_rollback($conexao);
        
        error_log("Erro na venda: " . $e->getMessage());
        header("Location: venda.php?erro=processamento&msg=" . urlencode($e->getMessage()));
        exit;
    }
    
} else {
    header("Location: venda.php");
    exit;
}
?>