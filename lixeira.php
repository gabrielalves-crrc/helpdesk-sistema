<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}

$stmt = $pdo->query("
    SELECT * FROM tickets
    WHERE status = 'excluido'
");
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lixeira</title>
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>

<h2>Lixeira</h2>

<?php foreach ($tickets as $t): ?>
    <div class="ticket-card">
        #<?= $t['id'] ?> — <?= htmlspecialchars($t['titulo']) ?>
    </div>
<?php endforeach; ?>

</body>
</html>
