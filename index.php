

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/login.css" />
    <link rel="stylesheet" href="assets/css/reset.css">
    <title>Login</title>
  </head>

  <body>
    <div class="container" id="container">
      <div class="form-container sign-in-container">
        <form action="autenticacao.php" method="POST">
          <h1>Login</h1>
          <input name="email" type="email" placeholder="Email" />
          <input name="password" type="password" placeholder="Password" />
          <button name="entrar" type="submit">Entrar</button>
        </form>
      </div>
      <div class="overlay">
        <div class="overlay-container">
          <h1>Bem-vindo a Nexus!</h1>
          <p>Faça seu Login.</p>
        </div>
      </div>
    </div>
  </body>
</html>
