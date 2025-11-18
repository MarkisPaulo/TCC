<?php
require_once("conexao.php");
require_once("verificaautenticacao.php");
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];
    $precoUnitarioDaCompra = $_POST['precoUnitarioDaCompra'];
    $precoUnitarioDaVenda = $_POST['precoUnitarioDaVenda'];
    $quantEstoque = $_POST['quantEstoque'];
    $ncm = $_POST['ncm'];
    $cfop = $_POST['cfop'];
    $unidMeidida = $_POST['unidMedida'];
    $idMarca = $_POST['idMarca'];
    $idCategoria = $_POST['idCategoria'];

    $sql = "INSERT INTO produto (nome, precoUnitarioDaVenda, precoUnitarioDaCompra, quantEstoque, ncm, cfop, idMarca, idCategoria, unidMedida) 
    VALUES('$nome', '$precoUnitarioDaVenda', '$precoUnitarioDaCompra', '$quantEstoque', '$ncm', '$cfop', '$idMarca', '$idCategoria', '$unidMeidida')";

    mysqli_query($conexao, $sql);
    echo "Registro salvo com sucesso";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/formCadastro.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Cadastro de Produto</title>
</head>

<body>
    <?php require_once("header.php"); ?>
    <div class="container">
        <div class="form-header">
            <h1><i class="fas fa-box"></i> Cadastro de Produto</h1>
            <p>Preencha os dados abaixo para cadastrar um novo produto</p>
        </div>

        <div class="info-box">
            <p><i class="fas fa-info-circle"></i> Campos marcados com * são obrigatórios</p>
        </div>

        <form method="POST">

            <div class="form-group">
                <label for="nome">Nome do Produto*</label>
                <input type="text" id="nome" name="nome" placeholder="Digite o nome do produto" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="precoUnitarioDaCompra">Preço Unitário da Compra*</label>
                    <input type="text" id="precoUnitarioDaCompra" name="precoUnitarioDaCompra" placeholder="R$ 0,00" required>
                </div>

                <div class="form-group">
                    <label for="precoUnitarioDaVenda">Preço Unitário da Venda*</label>
                    <input type="text" id="precoUnitarioDaVenda" name="precoUnitarioDaVenda" placeholder="R$ 0,00" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="product-category">Categoria*</label>
                    <select id="idCategoria" name="idCategoria" required>
                        <option value="">Selecione uma categoria</option>
                        <?php
                            $sql = "SELECT * FROM categoria ORDER BY nome";
                            $result = mysqli_query($conexao, $sql);

                            while ($row = mysqli_fetch_array($result)) { ?>
                                <option value="<?= $row['codigo'] ?>"><?= $row['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="product-marca">Marca*</label>
                    <select id="idMarca" name="idMarca" required>
                        <option value="">Selecione uma Marca</option>
                        <?php
                            $sql = "SELECT * FROM marca ORDER BY nome";
                            $result = mysqli_query($conexao, $sql);

                            while ($row = mysqli_fetch_array($result)) { ?>
                                <option value="<?= $row['codigo'] ?>"><?= $row['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantEstoque">Unidade Comercial*</label>
                    <select name="unidMedida" id="unidMedida" required>
                        <option value="">Seleciona uma Unidade</option>
                        <option value="UNID">UNIDADE</option>
                        <option value="CX">Caixa</option>
                        <option value="CENTO">CENTO</option>
                        <option value="CJ">CONJUNTO</option>
                        <option value="CM">CENTIMETRO</option>
                        <option value="CM2">CENTIMETRO QUADRADO</option>
                        <option value="JOGO">JOGO</option>
                        <option value="KG">QUILOGRAMA</option>
                        <option value="M">METRO</option>
                        <option value="M2">METRO QUADRADO</option>
                        <option value="M3">METRO CÚBICO</option>
                        <option value="MILHEI">MILHEIRO</option>
                        <option value="PC">PEÇA</option>
                        <option value="TON">TONELADA</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ncm">NCM*</label>
                    <input type="number" name="ncm" id="product-ncm" placeholder="Nomenclatura Comum do Mercosul" required>
                </div>
                <div class="form-group">
                    <label for="cfop">CFOP*</label>
                    <input type="number" name="cfop" id="cfop" placeholder="Código Fiscal de Operações e Prestações" required>
                </div>
                <div class="form-group">
                    <label for="quantEstoque">Quantidade em Estoque*</label>
                    <input type="number" id="quantEstoque" name="quantEstoque" placeholder="Quantidade disponível" min="1" required>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button type="submit" name="cadastrar" class="btn">Cadastrar Produto</button>
            </div>
        </form>
    </div>
</body>



<script>
    (function(){
        function formatCurrency(value){
            const digits = String(value).replace(/\D/g,'')
            const n = parseInt(digits || '0', 10)
            return (n/100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
        }

        function unformatToNumber(value){
            const digits = String(value).replace(/\D/g,'')
            const n = parseInt(digits || '0', 10)
            return (n/100).toFixed(2)
        }

        function attachFormatting(id){
            const el = document.getElementById(id)
            if(!el) return
            el.addEventListener('input', function(e){
                const cursorPos = el.selectionStart
                const old = el.value
                el.value = formatCurrency(old)
                // move cursor to end (safer que tentar restaurar complexo)
                el.setSelectionRange(el.value.length, el.value.length)
            })
            el.addEventListener('blur', function(){
                if(el.value.trim() === '') return
                el.value = formatCurrency(el.value)
            })
        }

        attachFormatting('precoUnitarioDaCompra')
        attachFormatting('precoUnitarioDaVenda')

        // Antes de submeter, normaliza para número com ponto (ex: 1234.56)
        const form = document.querySelector('form')
        if(form){
            form.addEventListener('submit', function(){
                const pc = document.getElementById('precoUnitarioDaCompra')
                const pv = document.getElementById('precoUnitarioDaVenda')
                if(pc && pc.value !== '') pc.value = unformatToNumber(pc.value)
                if(pv && pv.value !== '') pv.value = unformatToNumber(pv.value)
            })
        }
    })();
    </script>
</html>