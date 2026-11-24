<?php
session_start();
require_once 'conectar.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: metas.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['username']);
    $senha = $_POST['password'];
    $acao  = $_POST['acao'];

    if ($nome == '' || $senha == '') {
        $erro = 'Preencha o nome de usuário e a senha.';

    } elseif ($acao == 'cadastrar') {
        // Cadastro: bloqueia se o nome já existe
        $existe = R::findOne('usuarios', 'nome = ?', [$nome]);

        if ($existe) {
            $erro = 'Esse nome de usuário já existe.';
        } else {
            $usuario = R::dispense('usuarios');
            $usuario->nome  = $nome;
            $usuario->senha = password_hash($senha, PASSWORD_DEFAULT);
            R::store($usuario);

            $_SESSION['usuario_id']   = $usuario->id;
            $_SESSION['usuario_nome'] = $usuario->nome;
            header('Location: metas.php');
            exit;
        }

    } else {
        // Login
        $usuario = R::findOne('usuarios', 'nome = ?', [$nome]);

        if ($usuario && password_verify($senha, $usuario->senha)) {
            $_SESSION['usuario_id']   = $usuario->id;
            $_SESSION['usuario_nome'] = $usuario->nome;
            header('Location: metas.php');
            exit;
        } else {
            $erro = 'Usuário ou senha incorretos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minhas Metas - Entrar ou criar conta</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>

  <main class="main-card">
    <h1 class="brand-header">Minhas Metas</h1>

    <div class="grid-container">
      <section class="left-panel">
        <div>
          <div class="tag-line">Um passo de cada vez</div>
          <h2 class="hero-title">Pequenos passos.<br>Grandes conquistas.</h2>
          <p class="hero-description">Um lugar tranquilo para organizar suas metas e dar espaço ao que importa para você.</p>
          <div class="badge-pill">
            <span class="badge-icon">✓</span>
            <span>O seu ritmo também é progresso.</span>
          </div>
        </div>
        <p class="left-footer">Menos cobrança. Mais direção.</p>
      </section>

      <section class="right-panel">
        <form action="" method="POST">
          <div class="form-header">
            <h2>Bom ter você aqui.</h2>
            <p>Entre para continuar a sua jornada.</p>
          </div>

          <?php if ($erro != ''): ?>
            <p class="erro"><?= htmlspecialchars($erro) ?></p>
          <?php endif; ?>

          <div class="form-group">
            <label class="form-label" for="username">Nome do usuário</label>
            <input type="text" id="username" name="username" class="form-input" placeholder="Digite seu nome de usuário" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-input" placeholder="Digite sua senha" required>
          </div>

          <button type="submit" name="acao" value="entrar" class="btn-primary">
            <span>→</span> Entrar
          </button>

          <div class="divider">
            <span>ou comece agora</span>
          </div>

          <button type="submit" name="acao" value="cadastrar" class="btn-secondary">Cadastrar usuário</button>

          <p class="micro-copy">Seu próximo passo começa aqui. Sem custo.</p>
        </form>

        <footer class="page-footer">
          <div>
            <a href="#">Termos de uso</a>
            <a href="#">Privacidade</a>
          </div>
          <div style="margin-top: 4px;">© 2026 Minhas Metas. Feito para o seu tempo.</div>
        </footer>
      </section>
    </div>
  </main>

</body>
</html>