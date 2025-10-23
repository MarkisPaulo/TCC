<?php
require_once("verificaautenticacao.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Document</title>
</head>

<body>
    <header>
        <div class="sidebar" id="sidebar">
            <button class="toggle-btn" id="toggleBtn">
                <i class="fas fa-chevron-left"></i>
            </button>

            <!-- Logo com espaço para imagem -->
            <div class="logo-container">
                <div class="logo-img-placeholder">
                    <!-- SUA IMAGEM AQUI -->
                    <!-- <img src="caminho/para/sua-imagem.png" alt="NEXUS Logo"> -->
                </div>
                <div class="logo-text">NEXUS</div>
            </div>

            <nav>
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i> <span>Home</span></a></li>
                    <li><a href="#"><i class="fas fa-info-circle"></i> <span>About</span></a></li>
                    <li><a href="cadastros.php"><i class="fas fa-edit"></i> <span>Cadastros</span></a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> <span>Contact</span></a></li>
                </ul>
            </nav>
        </div>

    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleBtn');

            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
            });
        });
    </script>
</body>

</html>