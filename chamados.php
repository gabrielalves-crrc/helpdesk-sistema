<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SESSION['role'] === 'admin') {
    $stmt = $pdo->query("
        SELECT t.*, u.username
        FROM tickets t
        JOIN users u ON u.id = t.usuario_id
        ORDER BY t.criado_em DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username
        FROM tickets t
        JOIN users u ON u.id = t.usuario_id
        WHERE t.usuario_id = :id
        ORDER BY t.criado_em DESC
    ");
    $stmt->execute([':id' => $_SESSION['user_id']]);
}

$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Chamados</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
</head>

<body>

    <h2>Chamados</h2>

    <?php foreach ($tickets as $t): ?>
        <div class="ticket-card">
            <strong>#<?= $t['id'] ?> — <?= htmlspecialchars($t['titulo']) ?></strong><br>
            <small>Usuário: <?= htmlspecialchars($t['username']) ?></small><br>
            <small>Status: <?= $t['status'] ?></small>
            <p><?= nl2br(htmlspecialchars($t['descricao'])) ?></p>
        </div>
    <?php endforeach; ?>

</body>

</html>