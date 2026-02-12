<?php
require 'auth.php';
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

/* BUSCA CHAMADOS */
if ($_SESSION['role'] === 'admin') {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username
        FROM tickets t
        JOIN users u ON u.id = t.usuario_id
         WHERE (t.deleted IS NULL OR t.deleted = 0)
        ORDER BY t.criado_em DESC
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username
        FROM tickets t
        JOIN users u ON u.id = t.usuario_id
        WHERE t.usuario_id = :id
        AND (t.deleted IS NULL OR t.deleted = 0)
        ORDER BY t.criado_em DESC
    ");
    $stmt->execute([':id' => $_SESSION['user_id']]);
}

$tickets = $stmt->fetchAll();
?>

<?php
// ===== CONTAGEM DE STATUS PARA O TOPO =====
$contadores = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN status IN ('aberto', 'em_andamento') THEN 1 ELSE 0 END) as ativos,
        SUM(CASE WHEN status = 'fechado' THEN 1 ELSE 0 END) as resolvidos,
        SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) as em_andamento,
        COUNT(*) as total
    FROM tickets
    WHERE (deleted IS NULL OR deleted = 0)
    " . ($_SESSION['role'] === 'user' ? " AND usuario_id = :user_id" : "") . "
");

if ($_SESSION['role'] === 'user') {
    $contadores->execute([':user_id' => $_SESSION['user_id']]);
} else {
    $contadores->execute();
}

$stats = $contadores->fetch(PDO::FETCH_ASSOC);
?>

<?php
$pageTitle = "Dashboard - Painel Principal | HelpDesk CRRC";
$pageDescription = "Painel de controle do sistema de helpdesk. Visualize estatísticas, chamados recentes e atividades.";
$pageKeywords = "dashboard, painel controle, estatísticas, chamados, helpdesk";
$ogImage = "uploads/logotipo.png";

include 'assets/head/head.php';
?>

<body>
    <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <!-- HEADER DO SIDEBAR (SÓ UM!) -->
            <div class="sidebar-header">
                <span class="logo-text">HelpDesk</span>
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    ☰
                </button>
            </div>

            <!-- CONTEÚDO MOBILE (TUDO QUE ABRE/FECHA) -->
            <div class="mobile-content" id="mobileContent">
                <div class="top-actions">
                    <span class="user"><i class="fa-regular fa-user"></i><?= htmlspecialchars($_SESSION['username']) ?></span>
                    <div class="flex-section-top-actions">
                        <a href="logout.php" class="btn-logout">出去 / Sair</a>
                    </div>
                </div>

                <!-- SELETOR DE IDIOMA -->
                <div class="language-selector">
                    <div class="language-title">语言 / Idioma</div>
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

                <!-- MENU DE NAVEGAÇÃO -->
                <nav class="menu">
                    <a href="dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i>首页 / Home</a>

                    <?php if ($_SESSION['role'] === 'user'): ?>
                        <a href="novo_chamado.php" class="menu-item"><i class="fa-solid fa-plus"></i>新呼叫 / Novo Chamado</a>
                    <?php else: ?>
                        <a href="itens-enviados.php" class="menu-item"><i class="fa-solid fa-address-book"></i>发送 / Enviados</a>
                        <a href="lixeira.php" class="menu-item"><i class="fa-solid fa-trash"></i>垃圾桶 / Lixeira</a>
                    <?php endif; ?>
                </nav>
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
                                    <span class="stat-label">进行中 / Em andamento</span>
                                </div>
                            </div>
                            <div class="all-stat-value line-right">
                                <div class="stat-item">
                                    <span class="stat-value active"><?= $stats['ativos'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">资产 / Ativos</span>
                                </div>
                            </div>
                            <div class="all-stat-value line-right">
                                <div class="stat-item">
                                    <span class="stat-value resolved"><?= $stats['resolvidos'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">已解决 / Resolvidos</span>
                                </div>
                            </div>
                            <div class="all-stat-value">
                                <div class="stat-item">
                                    <span class="stat-value total"><?= $stats['total'] ?? 0 ?></span>
                                </div>
                                <div class="flex-stats">
                                    <span class="stat-label">全部的 / Total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="banner"></div>

            <!-- DASHBOARD -->
            <main class="dashboard">
                <h2>通话/CHAMADOS</h2>

                <?php if ($_SESSION['role'] === 'user'): ?>
                    <div style="margin-bottom:20px;">
                        <a href="novo_chamado.php" class="btn-primary">+ 新呼叫 / Novo Chamado</a>
                    </div>
                <?php endif; ?>

                <div id="skeleton-container">
                    <div class="skeleton-card">
                        <div class="skeleton skeleton-title"></div>
                        <div class="skeleton skeleton-line"></div>
                        <div class="skeleton skeleton-line"></div>
                        <div class="skeleton skeleton-line short"></div>
                    </div>
                </div>

                <div id="tickets-container" class="hidden">
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="ticket-card" id="ticket-<?= $ticket['id'] ?>">
                            <div class="ticket-header">
                                <strong>#<?= $ticket['id'] ?> — <?= htmlspecialchars($ticket['titulo']) ?></strong>
                                <span class="status <?= $ticket['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                </span>

                                <div class="ticket-actions">
                                    <button class="minimize-btn" onclick="toggleTicket(<?= $ticket['id'] ?>)">
                                        <i class="fas fa-minus"></i>
                                    </button>

                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <a href="delete_ticket.php?id=<?= $ticket['id'] ?>"
                                            class="delete-btn"
                                            onclick="return confirm('Mover este chamado para a lixeira?')"
                                            style="color: #dc3545; margin-left: 10px; text-decoration: none;">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- RESTO DO CONTEÚDO DO CHAMADO (IGUAL) -->
                            <div class="ticket-content" id="content-<?= $ticket['id'] ?>">
                                <!-- ... TODO O CONTEÚDO DO CHAMADO PERMANECE IGUAL ... -->
                                <div class="ticket-content" id="content-<?= $ticket['id'] ?>">

                                    <div class="ticket-info">
                                        <div><b>用户 / Usuário:</b> <?= htmlspecialchars($ticket['username']) ?></div>
                                        <div><b>创建于 / Criado em:</b> <?= $ticket['criado_em'] ?></div>
                                    </div>

                                    <div class="ticket-desc">
                                        <?php
                                        $descricao = $ticket['descricao'];
                                        $descricaoHTML = nl2br(htmlspecialchars($descricao));
                                        $id = $ticket['id'];
                                        ?>

                                        <p class="desc-text" id="desc-<?= $id ?>">
                                            <?= $descricaoHTML ?>
                                        </p>

                                        <button type="button"
                                            class="leia-mais-btn"
                                            id="btn-<?= $id ?>"
                                            onclick="toggleDescricao(<?= $id ?>)">阅读更多 / Leia mais...
                                        </button>
                                    </div>

                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <form method="POST" action="update_status.php" class="status-form">
                                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                            <select name="status">
                                                <option value="aberto">开放 / Aberto</option>
                                                <option value="em_andamento">进行中 / Em andamento</option>
                                                <option value="fechado">已关闭 / Fechado</option>
                                            </select>
                                            <button type="submit">更新 / Atualizar</button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- ===== COMENTÁRIOS ===== -->
                                    <div class="comments">
                                        <strong>评论 / Comentários</strong>

                                        <?php
                                        $c = $pdo->prepare("
                                                SELECT c.*, u.username
                                                FROM ticket_comments c
                                                JOIN users u ON u.id = c.user_id
                                                WHERE c.ticket_id = :id
                                                ORDER BY c.criado_em ASC
                                            ");
                                        $c->execute([':id' => $ticket['id']]);
                                        $comentarios = $c->fetchAll();
                                        ?>

                                        <?php foreach ($comentarios as $com): ?>
                                            <div class="comment <?= $com['user_id'] == $_SESSION['user_id'] ? 'me' : '' ?>">
                                                <b><?= htmlspecialchars($com['username']) ?></b>
                                                <p><?= nl2br(htmlspecialchars($com['comentario'])) ?></p>
                                                <span><?= $com['criado_em'] ?></span>
                                            </div>
                                        <?php endforeach; ?>

                                        <form method="POST" action="add_comment.php" enctype="multipart/form-data" class="comment-form">
                                            <textarea name="comment" placeholder="输入评论 / Digite um comentário..."></textarea>

                                            <label class="file-label">
                                                📎附加文件 / Anexar arquivo
                                                <input type="file" name="file" hidden>
                                            </label>

                                            <button type="submit">发送 / Enviar</button>

                                            <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                        </form>
                                    </div>

                                    <?php
                                    $files = $pdo->prepare("
                                        SELECT * FROM ticket_files
                                        WHERE ticket_id = :id");
                                    $files->execute([':id' => $ticket['id']]);
                                    $anexos = $files->fetchAll();
                                    ?>

                                    <?php if ($anexos): ?>
                                        <div class="attachments">
                                            <strong>附件 / Anexos</strong>
                                            <ul>
                                                <?php foreach ($anexos as $f): ?>
                                                    <li>
                                                        📎 <a href="<?= $f['file_path'] ?>" target="_blank">
                                                            <?= htmlspecialchars($f['file_name']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <!-- ===== HISTÓRICO ===== -->
                                    <div class="history">
                                        <strong>历史 / Histórico</strong>
                                        <?php
                                        $hist = $pdo->prepare("
                                                SELECT h.*, u.username
                                                FROM ticket_history h
                                                JOIN users u ON u.id = h.usuario_id
                                                WHERE h.ticket_id = :id
                                                ORDER BY h.criado_em DESC
                                            ");
                                        $hist->execute([':id' => $ticket['id']]);
                                        $historico = $hist->fetchAll();
                                        $totalHistorico = count($historico);
                                        ?>

                                        <?php if ($historico): ?>
                                            <!-- ÚLTIMO HISTÓRICO (sempre visível) -->
                                            <?php if (isset($historico[0])): ?>
                                                <div class="ultimo-historico">
                                                    <span class="historico-data"><?= $historico[0]['criado_em'] ?></span> —
                                                    <b><?= htmlspecialchars($historico[0]['username']) ?></b>:
                                                    <span class="historico-mudanca">
                                                        <?= $historico[0]['status_anterior'] ?> → <?= $historico[0]['status_novo'] ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>

                                            <!-- HISTÓRICO COMPLETO (escondido por padrão) -->
                                            <?php if ($totalHistorico > 1): ?>
                                                <div class="historico-completo" id="historico-<?= $ticket['id'] ?>" style="display: none;">
                                                    <ul>
                                                        <?php for ($i = 1; $i < $totalHistorico; $i++): ?>
                                                            <li>
                                                                <?= $historico[$i]['criado_em'] ?> —
                                                                <b><?= htmlspecialchars($historico[$i]['username']) ?></b>:
                                                                <?= $historico[$i]['status_anterior'] ?> → <?= $historico[$i]['status_novo'] ?>
                                                            </li>
                                                        <?php endfor; ?>
                                                    </ul>
                                                </div>

                                                <!-- BOTÃO VER MAIS -->
                                                <button type="button"
                                                    class="ver-historico-btn"
                                                    onclick="toggleHistorico(<?= $ticket['id'] ?>)"
                                                    id="btn-historico-<?= $ticket['id'] ?>">查看完整历史记录 / Ver histórico completo (<?= $totalHistorico - 1 ?> mais)
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <em>没有历史 / Sem histórico</em>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">↑</button>
</body>

</html>