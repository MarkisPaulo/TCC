<?php

session_start();

if (!isset($_SESSION['codigo'])){
    $mensagem = "Sessão expirada. Faça o login novamente.";
    header("location: index.php?mensagem=$mensagem");
}
?>

