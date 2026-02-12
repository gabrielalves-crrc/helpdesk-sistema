<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?erro=permissao');
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        $check = $pdo->prepare("SELECT id FROM tickets WHERE id = ?");
        $check->execute([$id]);
        
        if ($check->rowCount() > 0) {
            $sql = "UPDATE tickets SET 
                    deleted = 1, 
                    deleted_at = NOW() 
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            header('Location: dashboard.php?sucesso=excluido');
            exit;
        }
    } catch (PDOException $e) {
        header('Location: dashboard.php?erro=banco');
        exit;
    }
}

header('Location: dashboard.php?erro=invalido');
exit;
?>