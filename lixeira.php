<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT t.*, u.username
    FROM tickets t
    JOIN users u ON u.id = t.usuario_id
    WHERE t.deleted = 1
    ORDER BY t.deleted_at DESC
");
$stmt->execute();
$tickets = $stmt->fetchAll();

$totalLixeira = count($tickets);

// if (isset($_POST['restore'])) {
//     $id = $_POST['ticket_id'];
//     $stmt = $pdo->prepare("
//         UPDATE tickets 
//         SET deleted = 0, deleted_at = NULL 
//         WHERE id = ?
//     ");
//     $stmt->execute([$id]);
//     header("Location: lixeira.php");
//     exit;
// }

if (isset($_POST['delete_permanent'])) {
    $id = $_POST['ticket_id'];
    
    try {
        $pdo->beginTransaction();
        
        // 1. Deleta comentários
        $pdo->prepare("DELETE FROM ticket_comments WHERE ticket_id = ?")->execute([$id]);
        
        // 2. Deleta arquivos
        $pdo->prepare("DELETE FROM ticket_files WHERE ticket_id = ?")->execute([$id]);
        
        // 3. Deleta histórico
        $pdo->prepare("DELETE FROM ticket_history WHERE ticket_id = ?")->execute([$id]);
        
        // 4. Deleta o ticket
        $pdo->prepare("DELETE FROM tickets WHERE id = ?")->execute([$id]);
        
        $pdo->commit();
        
        $_SESSION['mensagem'] = "Chamado #$id excluído permanentemente!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['erro'] = "Erro ao excluir: " . $e->getMessage();
    }
    
    header("Location: lixeira.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<?php

$pageTitle = "Lixeira - Itens Excluídos | HelpDesk CRRC";
$pageDescription = "Recupere ou gerencie itens excluídos do sistema de helpdesk da CRRC Brasil. Acesso temporário a chamados, usuários e registros removidos.";
$pageKeywords = "lixeira, itens excluídos, recuperar dados, chamados deletados, restauração, backup, helpdesk CRRC";
$ogImage = "https://helpdesk.crrc.com.br/uploads/lixeira-preview.jpg";

include 'assets/head/head.php';
?>

<body>
    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="logo-text">HelpDesk</span>
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    ☰
                </button>
            </div>

            <div class="mobile-content" id="mobileContent">
                <div class="top-actions">
                    <span class="user"><i class="fa-regular fa-user"></i><?= htmlspecialchars($_SESSION['username']) ?></span>

                    <div class="flex-section-top-actions">
                        <a href="logout.php" class="btn-logout">出去 / Sair</a>
                    </div>
                </div>

                <div class="language-selector">
                    <div class="language-title"> 语言 / Idioma</div>
                    <div class="lang-links">
                        <a href="https://translate.google.com/translate?hl=zh-CN&sl=auto&tl=zh-CN&u=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                            target="_blank" class="lang-link">
                            <div class="flex">
                                <div class="flex-lenguage">
                                    <span class="flag">🇨🇳</span>
                                    <span>中文</span>
                                </div>
                                <div class="flex-icon">
                                    <img src="./uploads/ch2.png" alt="">
                                </div>
                            </div>
                        </a>

                        <a href="https://translate.google.com.br/translate?hl=pt-BR&sl=auto&tl=pt&u=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                            target="_blank" class="lang-link">

                            <div class="flex">
                                <div class="flex-lenguage">
                                    <span class="flag">🇧🇷</span>
                                    <span>Português</span>
                                </div>
                                <div class="flex-icon">
                                    <img src="./uploads/br2.png" alt="">
                                </div>
                            </div>
                        </a>
                        <a href="https://translate.google.com/translate?hl=en&sl=auto&tl=en&u=<?php echo urlencode((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>"
                            target="_blank" class="lang-link">

                            <div class="flex">
                                <div class="flex-lenguage">
                                    <span class="flag">🇺🇸</span>
                                    <span>English</span>
                                </div>
                                <div class="flex-icon">
                                    <img src="./uploads/en2.png" alt="">
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <nav class="menu">
                    <a href="dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i>首页 / Home</a>

                    <?php if ($_SESSION['role'] === 'user'): ?>
                        <a href="novo_chamado.php" class="menu-item"><i class="fa-solid fa-plus"></i>新呼叫 / Novo Chamado</a>
                    <?php else: ?>
                        <a href="itens-enviados.php" class="menu-item"><i class="fa-solid fa-address-book"></i>发送 / Enviados</a>
                        <a href="lixeira.php" class="menu-item active"><i class="fa-solid fa-trash"></i>垃圾桶 / Lixeira</a>
                        <a href="admin_create_user.php" class="menu-item"><i class="fa-solid fa-user-plus"></i>创建用户 / Criar Usuário</a>
                    <?php endif; ?>
                </nav>
                <!-- <div class="flex-icon-dark">
                <button id="toggleDark" class="dark-btn">🌙</button>
            </div> -->
            </div>
        </aside>

        <div class="main">
            <header class="topbar">
                <div class="logo">
                    <img src="uploads/logotipo-att.jpeg" alt="Logo" class="logo-img">
                </div>

                <div class="stats-bar">
                    <div class="all-stat-value">
                        <div class="stat-item">
                            <span class="stat-value total"><?= $totalLixeira ?></span>
                        </div>
                        <div class="flex-stats">
                            <span class="stat-label">Na Lixeira</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="banner3"></div>

            <main class="dashboard">
                <h2>已删除通话 / CHAMADOS EXCLUÍDOS</h2>
                <p style="margin-bottom: 20px; color: #666;">
                    Chamados movidos para lixeira. Você pode restaurá-los ou excluir permanentemente.
                </p>

                <?php if (empty($tickets)): ?>
                    <div class="empty-trash">
                        <i class="fas fa-trash-alt fa-3x"></i>
                        <p>A lixeira está vazia</p>
                        <a href="chamados.php" class="btn-primary">Voltar para Chamados</a>
                    </div>
                <?php else: ?>
                    <div class="tickets-list">
                        <?php foreach ($tickets as $t): ?>
                            <div class="ticket-card deleted">
                                <div class="ticket-header">
                                    <strong>#<?= $t['id'] ?> — <?= htmlspecialchars($t['titulo']) ?></strong>
                                    <span class="status deleted-status">Excluído</span>
                                    <span class="deleted-badge">Excluído em: <?= $t['deleted_at'] ?></span>
                                </div>

                                <div class="ticket-info">
                                    <div><b>用户 / Usuário:</b> <?= htmlspecialchars($t['username']) ?></div>
                                    <div><b>创建于 / Criado em:</b> <?= $t['criado_em'] ?></div>
                                    <div><b>Status original:</b> <?= ucfirst($t['status']) ?></div>
                                </div>

                                <div class="ticket-actions-trash">
                                    <!-- <form method="POST" style="display:inline;">
                                        <input type="hidden" name="ticket_id" value="< ?= $t['id'] ?>">
                                        <button type="submit" name="restore" class="btn-restore">
                                            <i class="fas fa-undo"></i> Restaurar Chamado
                                        </button>
                                    </form> -->

                                    <form method="POST" style="display:inline;"
                                        onsubmit="return confirm('Excluir PERMANENTEMENTE o chamado #<?= $t['id'] ?>?\n\nEsta ação NÃO pode ser desfeita!');">
                                        <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                                        <button type="submit" name="delete_permanent" class="btn-delete-permanent">
                                            <i class="fas fa-trash-alt"></i> Excluir Permanentemente
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Botão Voltar ao Topo -->
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">↑</button>

    <script src="assets/js/script.js"></script>
</body>

</html>