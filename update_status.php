<?php
require 'config.php';
require 'auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Requisição inválida.');
}

if ($_SESSION['role'] !== 'admin') {
    die('Acesso negado.');
}

$ticket_id = $_POST['ticket_id'] ?? null;
$novo_status = $_POST['status'] ?? null;

if (!$ticket_id || !$novo_status) {
    die('Dados inválidos.');
}

/* 1️⃣ BUSCA STATUS ATUAL */
$stmt = $pdo->prepare("
    SELECT status 
    FROM tickets 
    WHERE id = :id
");
$stmt->execute([':id' => $ticket_id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    die('Chamado não encontrado.');
}

$status_anterior = $ticket['status'];

/* 2️⃣ ATUALIZA O STATUS DO CHAMADO */
$stmt = $pdo->prepare("
    UPDATE tickets 
    SET status = :status 
    WHERE id = :id
");
$stmt->execute([
    ':status' => $novo_status,
    ':id' => $ticket_id
]);

/* 3️⃣ SALVA HISTÓRICO */
$stmt = $pdo->prepare("
    INSERT INTO ticket_history 
    (ticket_id, usuario_id, status_anterior, status_novo)
    VALUES (:ticket_id, :usuario_id, :status_anterior, :status_novo)
");
$stmt->execute([
    ':ticket_id' => $ticket_id,
    ':usuario_id' => $_SESSION['user_id'],
    ':status_anterior' => $status_anterior,
    ':status_novo' => $novo_status
]);

header("Location: dashboard.php");
exit;
