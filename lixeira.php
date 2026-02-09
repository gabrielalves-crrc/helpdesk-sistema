<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}

/* BUSCAR CHAMADOS EXCLUÍDOS */
$stmt = $pdo->prepare("
    SELECT t.*, u.username
    FROM tickets t
    JOIN users u ON u.id = t.usuario_id
    WHERE t.deleted = 1
    ORDER BY t.deleted_at DESC
");
$stmt->execute();
$tickets = $stmt->fetchAll();

/* CONTAGEM PARA O MENU (se quiser) */
$totalLixeira = count($tickets);

// Restaurar chamado
if (isset($_POST['restore'])) {
    $id = $_POST['ticket_id'];
    $stmt = $pdo->prepare("
        UPDATE tickets 
        SET deleted = 0, deleted_at = NULL 
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    header("Location: lixeira.php");
    exit;
}

// Deletar permanentemente
if (isset($_POST['delete_permanent'])) {
    $id = $_POST['ticket_id'];
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: lixeira.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Lixeira - HelpDesk</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'pt',
                includedLanguages: 'pt,en,zh-CN,es,fr',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="app">
        <!-- SIDEBAR (MESMA DO DASHBOARD) -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="logo-text">HelpDesk</span>
            </div>
            <div class="top-actions">
                <span class="user"><i class="fa-regular fa-user"></i><?= htmlspecialchars($_SESSION['username']) ?></span>

                <div class="flex-section-top-actions">
                    <a href="logout.php" class="btn-logout">Sair</a>
                </div>
            </div>

            <!-- ===== SELETOR DE IDIOMA ===== -->
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
                <a href="dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i></i>Home</a>

                <?php if ($_SESSION['role'] === 'user'): ?>
                    <a href="novo_chamado.php"><i class="fa-solid fa-plus"></i> Novo Chamado</a>
                <?php endif; ?>

                <a href="itens-enviados.php" class="menu-item"><i class="fa-solid fa-address-book"></i> Itens enviados</a>
                <a href="lixeira.php" class="menu-item"><i class="fa-solid fa-trash"></i> Lixeira</a>
            </nav>
            <div class="flex-icon-dark">
                <button id="toggleDark" class="dark-btn">🌙</button>
            </div>
        </aside>

        <!-- CONTEÚDO PRINCIPAL -->
        <div class="main">
            <!-- TOPO (MESMO DO DASHBOARD) -->
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
            
            <div class="banner"></div>
            
            <!-- CONTEÚDO DA LIXEIRA -->
            <main class="dashboard">
                <h2>🗑️ CHAMADOS EXCLUÍDOS</h2>
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
                                    <div><b>Usuário:</b> <?= htmlspecialchars($t['username']) ?></div>
                                    <div><b>Criado em:</b> <?= $t['criado_em'] ?></div>
                                    <div><b>Status original:</b> <?= ucfirst($t['status']) ?></div>
                                </div>

                                <div class="ticket-actions-trash">
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="ticket_id" value="<?= $t['id'] ?>">
                                        <button type="submit" name="restore" class="btn-restore">
                                            <i class="fas fa-undo"></i> Restaurar Chamado
                                        </button>
                                    </form>

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