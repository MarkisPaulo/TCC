<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cadastros.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Portal de Cadastros</title>
    <style>
        
    </style>
</head>
<body>

    <?php require_once('header.php');?>
    <div class="header">
        <div class="container">
            <div class="logo">Sistema de Gestão</div>
            <div class="subtitle">Portal de Cadastros</div>
        </div>
    </div>
    
    <main class="container">
        <div class="cards-container">
            <div class="card" onclick="navegarPara('cliente')">
                <div class="card-icon"><i class="fas fa-thin fa-user"></i></div>
                <div class="card-title">Cadastro de Clientes</div>
                <div class="card-description">Gerencie informações de clientes, contatos e histórico de relacionamento.</div>
            </div>
            
            <div class="card" onclick="navegarPara('produto')">
                <div class="card-icon"><i class="fas fa-box"></i></div>
                <div class="card-title">Cadastro de Produtos</div>
                <div class="card-description">Adicione, edite e visualize produtos, estoque e informações de preços.</div>
            </div>
            
            <div class="card" onclick="navegarPara('funcionario')">
                <div class="card-icon"><i class="fas fa-thin fa-user-tie"></i></div>
                <div class="card-title">Cadastro de Funcionários</div>
                <div class="card-description">Controle dados de fornecedores, contratos e informações de contato.</div>
            </div>
            
            <div class="card" onclick="navegarPara('categoria')">
                <div class="card-icon"><i class="fas fa-thin fa-tags"></i></div>
                <div class="card-title">Cadastro de Categoria</div>
                <div class="card-description">Administre permissões, acessos e dados dos usuários do sistema.</div>
            </div>

            <div class="card" onclick="navegarPara('marca')">
                <div class="card-icon"><i class="fas fa-thin fa-registered"></i></div>
                <div class="card-title">Cadastro de Marca</div>
                <div class="card-description">Administre permissões, acessos e dados dos usuários do sistema.</div>
            </div>
        </div>
    </main>

    
    <script>
        function navegarPara(tela) {
            window.location.href = `${tela}-cadastrar.php`;
        }
    </script>
</body>
</html>