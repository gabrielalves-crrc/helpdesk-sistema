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

<?php include 'assets/head/head.php' ?>

<body>

    <h2>Chamados</h2>

    <?php foreach ($tickets as $t): ?>
        <div class="ticket-card">
            <strong>#<?= $t['id'] ?> — <?= htmlspecialchars($t['titulo']) ?></strong><br>
            <small>用户 / Usuário: <?= htmlspecialchars($t['username']) ?></small><br>
            <small>Status: <?= $t['status'] ?></small>
            <p><?= nl2br(htmlspecialchars($t['descricao'])) ?></p>
        </div>
    <?php endforeach; ?>

    <!-- Botão Voltar ao Topo -->
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">
        ↑
    </button>
    <script src="assets/js/script.js"></script>
</body>

</html>