<?php
require 'auth.php';
require 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $usuario_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare(
        "INSERT INTO tickets (titulo, descricao, usuario_id)
         VALUES (:titulo, :descricao, :usuario_id)"
    );

    $stmt->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':usuario_id' => $usuario_id
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<h2>Abrir Chamado</h2>

<form method="POST">
    <label>Título:</label><br>
    <input type="text" name="titulo" required><br><br>

    <label>Descrição:</label><br>
    <textarea name="descricao" required></textarea><br><br>

    <button type="submit">Criar Chamado</button>
</form>

<br>
<a href="dashboard.php">Voltar</a>
