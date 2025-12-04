<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
require_once("notificacoes.php");

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega os itens do carrinho da sessão
    $itens = $_SESSION['carrinho'];


    // DADOS DO PAGAMENTO
    $idCliente = (int) $_POST['idCliente'];
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

    $temEntrega = isset($_POST['temEntrega']) && $_POST['temEntrega'] == 1 ? 1 : 0;
    $enderecoEntrega = null;
    $dataHoraEntrega = null;
    $statusEntrega = 0; // 0 = sem entrega
    $erros = [];

    if ($temEntrega == 1) {
        $dataEntrega = $_POST['dataEntrega']; // DD/MM/YYYY
        $horaEntrega = $_POST['horaEntrega']; // HH:MM

        // Cria objeto DateTime SEM fazer format() ainda
        $dataFormatada = \DateTime::createFromFormat('d/m/Y', $dataEntrega);
        $dataHoje = new \DateTime();

        // Reseta horas para comparar apenas datas
        $dataFormatada->setTime(0, 0, 0);
        $dataHoje->setTime(0, 0, 0);

        // Validação 1: Data não pode ser anterior a hoje
        $diff = $dataFormatada->diff($dataHoje);
        if ($diff->invert === 0 && $diff->days > 0) {
            $erros[] = "A data de entrega não pode ser anterior a hoje.";
        }

        // Validação 2: Se a data for hoje, a hora não pode ser anterior à hora atual
        if ($diff->days === 0) { // Mesma data (hoje)
            // Converte as horas para timestamp para comparação
            $horaAtual = strtotime(date('H:i'));
            $horaEntregaStr = strtotime($horaEntrega);

            if ($horaEntregaStr < $horaAtual) {
                $erros[] = "A hora de entrega não pode ser anterior à hora atual.";
            }
        }

        // Se houver erros, retorna com mensagem
        if (!empty($erros)) {
            $_SESSION['erro_entrega'] = implode('<br>', $erros);
            header('Location: venda.php');
            exit();
        }

        // Agora sim, converte para string para salvar no banco
        $dataHoraEntrega = $dataFormatada->format('Y-m-d') . ' ' . $horaEntrega;
        $enderecoEntrega = $_POST['enderecoEntrega'];
        $statusEntrega = 1; // 1 = pendente (agendado)
    }

    // 1. Insere a VENDA
    $sqlVenda = "INSERT INTO vendas (valorTotal, formaDeRecebimento, observacoes, idFuncionario, idCliente, 
                                    dataHoraEntrega, enderecoEntrega, statusEntrega) 
                VALUES ($valorTotal, '$formaRecebimento', '$observacoes', $idFuncionario, $idCliente,
                        " . ($dataHoraEntrega ? "'$dataHoraEntrega'" : "NULL") . ",
                        " . ($enderecoEntrega ? "'$enderecoEntrega'" : "NULL") . ",
                        $statusEntrega)";

    mysqli_query($conexao, $sqlVenda);

    $numeroDaVenda = mysqli_insert_id($conexao);

    // 2. Insere itens da venda
    foreach ($itens as $item) {
        $codigoProduto = (int) $item['codigo'];
        $quantidade = (int) $item['quantidade'];
        $precoUnitario = (float) $item['precoUnitario'];

        $sqlItem = "INSERT INTO vendahasproduto (FkNumeroDaVenda, FkCodigoProduto, quantidade, precoUnitDaVenda) 
                    VALUES ($numeroDaVenda, $codigoProduto, $quantidade, $precoUnitario)";

        mysqli_query($conexao, $sqlItem);

        // Atualiza o estoque
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
    $dataRecebimento = $statusRecebimento == 1 ? date('d-m-Y') : '00-00-0000';

    $sqlRecebimento = "INSERT INTO recebimentos 
        (formaDeRecebimento, valorRecebido, valorReceber, dataVencimento, dataRecebimento, status, idVenda) 
        VALUES ('$formaRecebimento', $valorRecebido, $valorReceber, '$dataVencimento', '$dataRecebimento', $statusRecebimento, $numeroDaVenda)";

    mysqli_query($conexao, $sqlRecebimento);

    // 4. LIMPA O CARRINHO E CLIENTE DA SESSÃO
    unset($_SESSION['carrinho']);
    unset($_SESSION['cliente_venda']);
    $_SESSION['carrinho'] = array(); // Reinicia como array vazio

    setNotificacao('venda', 'Total: R$ ' . number_format($valorTotal, 2, ',', '.'), $numeroDaVenda);
    header("Location: venda.php");
    exit;
}
?>