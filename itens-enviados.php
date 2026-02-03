<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}

$stmt = $pdo->query("
    SELECT t.*, u.username
    FROM tickets t
    JOIN users u ON u.id = t.usuario_id
    WHERE t.status = 'fechado'
    ORDER BY t.criado_em DESC
");
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Itens enviados</title>
</head>

<body>

    <h2>Itens enviados</h2>

    <?php foreach ($tickets as $t): ?>
        <div class="ticket-card">
            <strong>#<?= $t['id'] ?> — <?= htmlspecialchars($t['titulo']) ?></strong><br>
            <small><?= htmlspecialchars($t['username']) ?></small>
        </div>
    <?php endforeach; ?>

</body>

</html>