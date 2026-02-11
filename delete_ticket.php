<?php
session_start();
require_once 'config.php';

// VERIFICAÇÃO CORRETA - USANDO user_id (que é o que VOCÊ tem)
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// SÓ ADMIN PODE EXCLUIR (role é o que VOCÊ tem)
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?erro=permissao');
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        // VERIFICA SE O CHAMADO EXISTE
        $check = $pdo->prepare("SELECT id FROM tickets WHERE id = ?");
        $check->execute([$id]);
        
        if ($check->rowCount() > 0) {
            // MARCA COMO EXCLUÍDO
            $sql = "UPDATE tickets SET 
                    deleted = 1, 
                    deleted_at = NOW() 
                    WHERE id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            // REDIRECIONA DE VOLTA COM SUCESSO
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