<?php
require_once("conexao.php");

if (isset($_POST['entrar'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM funcionario WHERE email = '{$email}' and senha = '{$password}' and status = 1 ";

    $result = mysqli_query($conexao , $sql);

    if (mysqli_num_rows($result) > 0) {
        $funcionario = mysqli_fetch_array($result);

        session_start();
        $_SESSION["codigo"] = $funcionario["codigo"];
        $_SESSION["nome"] = $funcionario["nome"];
        $_SESSION["email"] = $funcionario["email"];

        header("location: adm.php");
    }else{
        $mensagem = "Usuário/Senha inválidos.";
        header("location: index.php?mensagem=$mensagem");
    }
}

?>