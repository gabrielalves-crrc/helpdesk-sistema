<?php
require 'auth.php';
require 'config.php';

$ticket_id = $_POST['ticket_id'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

if (!$ticket_id || !$comentario) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO ticket_comments (ticket_id, user_id, comentario)
    VALUES (:ticket_id, :user_id, :comentario)
");

$stmt->execute([
    ':ticket_id' => $ticket_id,
    ':user_id' => $_SESSION['user_id'],
    ':comentario' => $comentario
]);

header("Location: dashboard.php");
exit;
