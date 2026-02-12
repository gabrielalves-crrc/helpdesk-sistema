<?php
require 'config.php';
session_start();

$ticket_id = $_POST['ticket_id'];
$comment = $_POST['comment'] ?? null;
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    INSERT INTO ticket_comments (ticket_id, user_id, comentario)
    VALUES (:ticket, :user, :comentario)
");
$stmt->execute([
    ':ticket' => $ticket_id,
    ':user' => $user_id,
    ':comentario' => $comment
]);

if (!empty($_FILES['file']['name'])) {
    $allowed = ['png','jpg','jpeg','pdf'];
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if (in_array(strtolower($ext), $allowed)) {
        $dir = "uploads/";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $newName = uniqid() . "." . $ext;
        $path = $dir . $newName;

        move_uploaded_file($_FILES['file']['tmp_name'], $path);

        $stmt = $pdo->prepare("
            INSERT INTO ticket_files (ticket_id, user_id, file_name, file_path)
            VALUES (:ticket, :user, :name, :path)
        ");
        $stmt->execute([
            ':ticket' => $ticket_id,
            ':user' => $user_id,
            ':name' => $_FILES['file']['name'],
            ':path' => $path
        ]);
    }
}

header("Location: dashboard.php");
exit;