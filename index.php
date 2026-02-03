<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - HelpDesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body class="login-page">

<div class="login-card">
    <h1>HelpDesk</h1>
    <p class="subtitle">Acesso ao sistema</p>

    <?php if (isset($_GET['erro'])): ?>
        <div class="error">Usuário ou senha inválidos</div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Usuário</label>
        <input type="text" name="username" required>

        <label>Senha</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn-primary">Entrar</button>
    </form>

    <div class="login-card-footer">
        © <?= date('Y') ?> HelpDesk
    </div>
</div>

</body>
</html>
