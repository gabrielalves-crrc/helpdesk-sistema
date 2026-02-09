<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}

$stmt = $pdo->query("
    SELECT t.*, u.username
    FROM tickets t
    JOIN users u ON u.id = t.usuario_id
    WHERE t.status = 'fechado'
    ORDER BY t.criado_em DESC
");
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Itens enviados</title>
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
        <!-- SIDEBAR -->
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

            <!-- ===== SELETOR DE IDIOMA SIMPLIFICADO ===== -->
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

        <!-- CONTEÚDO -->
        <div class="main">
            <!-- TOPO -->
            <header class="topbar">
                <div class="logo">
                    <img src="uploads/logotipo-att.jpeg" alt="Logo" class="logo-img">
                </div>

                <div class="status-toggle-container">
                    <button class="status-toggle-btn" id="statusToggle">
                        <svg class="toggle-icon" width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z" />
                        </svg>
                        <span class="toggle-text">地位 / Status</span>
                    </button>

                    <div class="status-content" id="statusContent">
                        <div class="stats-bar">
                            <div class="all-stat-value line-right">
                                <div class="stat-item">
                                    <span class="stat-value in-progress"><?= $stats['em_andamento'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">Em andamento</span>
                                </div>
                            </div>

                            <div class="all-stat-value line-right">
                                <div class="stat-item">
                                    <span class="stat-value active"><?= $stats['ativos'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">Ativos</span>
                                </div>
                            </div>

                            <div class="all-stat-value line-right">
                                <div class="stat-item">
                                    <span class="stat-value resolved"><?= $stats['resolvidos'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">Resolvidos</span>
                                </div>
                            </div>

                            <div class="all-stat-value">
                                <div class="stat-item">
                                    <span class="stat-value total"><?= $stats['total'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">Total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="dashboard">
                <h2>Itens enviados</h2>
                <?php foreach ($tickets as $t): ?>
                    <div class="ticket-card">
                        <strong>#<?= $t['id'] ?> — <?= htmlspecialchars($t['titulo']) ?></strong><br>
                        <small><?= htmlspecialchars($t['username']) ?></small>
                    </div>
                <?php endforeach; ?>
            </main>

        </div>
    </div>
    <!-- Botão Voltar ao Topo -->
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">
        ↑
    </button>

    <script src="assets/js/script.js"></script>
</body>

</html>