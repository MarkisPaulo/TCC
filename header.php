<?php
require_once("verificaautenticacao.php");
?>

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
                    <li><a href="adm.php"><i class="fas fa-home"></i> <span>Home</span></a></li>
                    
                    <li class="has-dropdown">
                        <a href="#" class="dropdown-toggle"><i class="fas fa-edit"></i> <span>Cadastros</span><i class="fas fa-chevron-down caret"></i></a>
                        <ul class="dropdown">
                            <li><a href="categoria-cadastrar.php"><i class="fas fa-thin fa-tags"></i> <span>Categoria</span></a></li>
                            <li><a href="cliente-cadastrar.php"><i class="fas fa-thin fa-user"></i> <span>Cliente</span></a></li>
                            <li><a href="funcionario-cadastrar.php"><i class="fas fa-thin fa-user-tie"></i> <span>Funcionário</span></a></li>
                            <li><a href="marca-cadastrar.php"><i class="fas fa-thin fa-registered"></i> <span>Marca</span></a></li>
                            <li><a href="produto-cadastrar.php"><i class="fas fa-box"></i> <span>Produto</span></a></li>
                        </ul>
                    </li>
                    
                    <li class="has-dropdown">
                        <a href="#" class="dropdown-toggle"><i class="fas fa-list"></i> <span>Listagem</span><i class="fas fa-chevron-down caret"></i></a>
                        <ul class="dropdown">
                            <li><a href="categoria-listar.php"><i class="fas fa-thin fa-tags"></i> <span>Categoria</span></a></li>
                            <li><a href="cliente-listar.php"><i class="fas fa-thin fa-user"></i> <span>Cliente</span></a></li>
                            <li><a href="funcionario-listar.php"><i class="fas fa-thin fa-user-tie"></i> <span>Funcionário</span></a></li>
                            <li><a href="marca-listar.php"><i class="fas fa-thin fa-registered"></i> <span>Marca</span></a></li>
                            <li><a href="produto-listar.php"><i class="fas fa-box"></i> <span>Produto</span></a></li>
                        </ul>
                    </li>
                    <li><a href="venda.php"><i class="fas fa-store"></i> <span>Venda</span></a></li>
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
                document.body.classList.toggle('sidebar-collapsed');
            });

        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const li = btn.closest('.has-dropdown');
                    li.classList.toggle('open');
                });
            });

            // Fecha dropdown ao clicar fora
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.has-dropdown')) {
                    document.querySelectorAll('.has-dropdown.open').forEach(openEl => openEl.classList.remove('open'));
                }
            });
        });
    </script>