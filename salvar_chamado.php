<?php
require 'config.php';
require 'auth.php';

if ($_SESSION['role'] !== 'user') {
    die('Acesso negado');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Requisição inválida');
}

$titulo = $_POST['titulo'] ?? '';
$descricao = $_POST['descricao'] ?? '';

if (!$titulo || !$descricao) {
    die('Dados inválidos');
}

$stmt = $pdo->prepare("
    INSERT INTO tickets (titulo, descricao, usuario_id, status, criado_em)
    VALUES (:titulo, :descricao, :usuario_id, 'aberto', NOW())
");

$stmt->execute([
    ':titulo' => $titulo,
    ':descricao' => $descricao,
    ':usuario_id' => $_SESSION['user_id']
]);

header('Location: dashboard.php');
exit;
