<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/cadastros.css">
    <title>Portal de Cadastros</title>
    <style>
        
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Sistema de Gestão</div>
            <div class="subtitle">Portal de Cadastros</div>
        </div>
    </header>
    
    <main class="container">
        <div class="cards-container">
            <div class="card" onclick="navegarPara('clientes')">
                <div class="card-icon">👥</div>
                <div class="card-title">Cadastro de Clientes</div>
                <div class="card-description">Gerencie informações de clientes, contatos e histórico de relacionamento.</div>
            </div>
            
            <div class="card" onclick="navegarPara('produtos')">
                <div class="card-icon">📦</div>
                <div class="card-title">Cadastro de Produtos</div>
                <div class="card-description">Adicione, edite e visualize produtos, estoque e informações de preços.</div>
            </div>
            
            <div class="card" onclick="navegarPara('fornecedores')">
                <div class="card-icon">🏭</div>
                <div class="card-title">Cadastro de Fornecedores</div>
                <div class="card-description">Controle dados de fornecedores, contratos e informações de contato.</div>
            </div>
            
            <div class="card" onclick="navegarPara('usuarios')">
                <div class="card-icon">👤</div>
                <div class="card-title">Cadastro de Usuários</div>
                <div class="card-description">Administre permissões, acessos e dados dos usuários do sistema.</div>
            </div>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2023 Sistema de Gestão - Todos os direitos reservados</p>
        </div>
    </footer>
    
    <script>
        function navegarPara(tela) {
            // Aqui você pode implementar a navegação para as telas específicas
            alert(`Navegando para a tela de: ${tela}`);
            // Exemplo: window.location.href = `/cadastro-${tela}.html`;
        }
    </script>
</body>
</html>