<?php

function exibirNotificacao() {
    if (!isset($_SESSION['notificacao'])) {
        return;
    }
    
    $notificacao = $_SESSION['notificacao'];
    $tipo = $notificacao['tipo'] ?? 'sucesso';
    $mensagem = $notificacao['mensagem'] ?? '';
    $numeroVenda = $notificacao['numeroVenda'] ?? null;
    
    unset($_SESSION['notificacao']);
    
    if ($tipo === 'venda') {
        ?>
        <div class="overlay-notificacao" id="overlayNotificacao"></div>
        <div class="notificacao-venda" id="notificacaoVenda">
            <div class="icone-sucesso">
                <i class="fas fa-check"></i>
            </div>
            <h2>Venda Realizada com Sucesso!</h2>
            <p class="numero-venda">#<?= $numeroVenda ?></p>
            <p><?= $mensagem ?></p>
        </div>
        <script>
            setTimeout(function() {
                var elemento = document.getElementById('notificacaoVenda');
                var overlay = document.getElementById('overlayNotificacao');
                if (elemento && overlay) {
                    elemento.style.animation = 'desaparecer 0.3s ease-in forwards';
                    overlay.style.animation = 'fadeIn 0.3s ease-in reverse forwards';
                    setTimeout(function() {
                        elemento.remove();
                        overlay.remove();
                    }, 300);
                }
            }, 3000);
        </script>
        <?php
        return;
    }
    
    $icones = [
        'sucesso' => 'fa-check-circle',
        'alerta' => 'fa-exclamation-triangle',
        'erro' => 'fa-times-circle'
    ];
    
    $icone = $icones[$tipo] ?? 'fa-info-circle';
    
    ?>
    <div class="notificacao <?= $tipo ?>" id="notificacao">
        <i class="fas <?= $icone ?>"></i>
        <div class="notificacao-texto"><?= $mensagem ?></div>
    </div>
    <script>
        setTimeout(function() {
            var elemento = document.getElementById('notificacao');
            if (elemento) {
                elemento.classList.add('desaparecer');
                setTimeout(function() {
                    elemento.remove();
                }, 300);
            }
        }, 3000);
    </script>
    <?php
}

function setNotificacao($tipo, $mensagem, $numeroVenda = null) {
    $_SESSION['notificacao'] = [
        'tipo' => $tipo,
        'mensagem' => $mensagem,
        'numeroVenda' => $numeroVenda
    ];
}
?>