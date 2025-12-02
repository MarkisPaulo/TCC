<?php
session_start();

if (isset($_POST['salvar_cliente'])) {
    $_SESSION['cliente_venda'] = array(
        'codigo' => $_POST['codigo'],
        'nome' => $_POST['nome'],
        'cpf_cnpj' => $_POST['cpf_cnpj']
    );
    echo "OK";
}
?>