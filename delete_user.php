<?php
require 'auth.php';
require 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "DEBUG: Chegou no delete_user.php<br>";
echo "ID: " . ($_GET['id'] ?? 'vazio') . "<br>";
echo "Sessão user_id: " . ($_SESSION['user_id'] ?? 'vazio') . "<br>";
echo "Role: " . ($_SESSION['role'] ?? 'vazio') . "<br>";

// SÓ ADMIN PODE EXCLUIR USUÁRIOS
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?erro=acesso_negado');
    exit;
}

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    // VERIFICA SE NÃO ESTÁ TENTANDO EXCLUIR A SI MESMO
    if ($id == $_SESSION['user_id']) {
        header('Location: admin_create_user.php?erro=auto_delete');
        exit;
    }

    try {
        // OPCIONAL: VERIFICAR SE USUÁRIO TEM CHAMADOS ABERTOS
        // $checkTickets = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE usuario_id = ? AND (deleted IS NULL OR deleted = 0)");
        // $checkTickets->execute([$id]);
        // $ticketsAbertos = $checkTickets->fetchColumn();

        // if ($ticketsAbertos > 0) {
        //     header('Location: admin_create_user.php?erro=tem_chamados&id=' . $id);
        //     exit;
        // }

        // INICIA TRANSAÇÃO
        $pdo->beginTransaction();

        // 1. DELETA COMENTÁRIOS DO USUÁRIO
        $pdo->prepare("DELETE FROM ticket_comments WHERE user_id = ?")->execute([$id]);
        
        // 2. DELETA ARQUIVOS DO USUÁRIO
        $pdo->prepare("DELETE FROM ticket_files WHERE user_id = ?")->execute([$id]);
        
        // 3. DELETA HISTÓRICO DO USUÁRIO
        $pdo->prepare("DELETE FROM ticket_history WHERE usuario_id = ?")->execute([$id]);
        
        // 4. DELETA TICKETS DO USUÁRIO (OU TRANSFERE PARA ADMIN?)
        $pdo->prepare("UPDATE tickets SET usuario_id = ? WHERE usuario_id = ?")->execute([1, $id]); // Transfere para admin ID 1
        
        // 5. DELETA O USUÁRIO
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);

        $pdo->commit();

        header('Location: admin_create_user.php?sucesso=usuario_excluido&id=' . $id);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Location: admin_create_user.php?erro=banco');
    }
} else {
    header('Location: admin_create_user.php?erro=invalido');
}
exit;
?>