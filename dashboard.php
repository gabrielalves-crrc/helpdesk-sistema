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
        ORDER BY t.criado_em DESC
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT t.*, u.username
        FROM tickets t
        JOIN users u ON u.id = t.usuario_id
        WHERE t.usuario_id = :id
        ORDER BY t.criado_em DESC
    ");
    $stmt->execute([':id' => $_SESSION['user_id']]);
}

/* CONTAGEM DE CHAMADOS ABERTOS */
if ($_SESSION['role'] === 'admin') {
    $qtd = $pdo->prepare("
        SELECT COUNT(*) 
        FROM tickets 
        WHERE status IN ('aberto', 'em_andamento')
    ");
    $qtd->execute();
} else {
    $qtd = $pdo->prepare("
        SELECT COUNT(*) 
        FROM tickets 
        WHERE status IN ('aberto', 'em_andamento')
        AND usuario_id = :id
    ");
    $qtd->execute([':id' => $_SESSION['user_id']]);
}

$totalAbertos = $qtd->fetchColumn();

$tickets = $stmt->fetchAll();

?>





<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>HelpDesk</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="app">

        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="logo-text">HelpDesk</span>

            </div>

            <nav class="menu">
                <a href="chamados.php" class="menu-item active">
                    📩 Chamados
                    <?php if ($totalAbertos > 0): ?>
                        <span class="badge"><?= $totalAbertos ?></span>
                    <?php endif; ?>
                </a>

                <?php if ($_SESSION['role'] === 'user'): ?>
                    <a href="novo_chamado.php">➕ Novo Chamado</a>
                <?php endif; ?>

                <a href="itens-enviados.php" class="menu-item">📤 Itens enviados</a>
                <a href="lixeira.php" class="menu-item">🗑️ Lixeira</a>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php" class="menu-item">⚙️ Administração</a>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- CONTEÚDO -->
        <div class="main">

            <!-- TOPO -->
            <header class="topbar">
                <div class="logo">HelpDesk</div>
                <div class="top-actions">
                    <button id="toggleDark" class="dark-btn">🌙</button>
                    <span class="user">👤 <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="btn-logout">Sair</a>
                </div>
            </header>

            <!-- DASHBOARD -->
            <main class="dashboard">
                <h2>Chamados</h2>

                <?php if ($_SESSION['role'] === 'user'): ?>
                    <div style="margin-bottom:20px;">
                        <a href="novo_chamado.php" class="btn-primary">+ Novo Chamado</a>
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
                        <div class="ticket-card">

                            <div class="ticket-header">
                                <strong>#<?= $ticket['id'] ?> — <?= htmlspecialchars($ticket['titulo']) ?></strong>
                                <span class="status <?= $ticket['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                </span>
                            </div>

                            <div class="ticket-info">
                                <div><b>Usuário:</b> <?= htmlspecialchars($ticket['username']) ?></div>
                                <div><b>Criado em:</b> <?= $ticket['criado_em'] ?></div>
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
                                    onclick="toggleDescricao(<?= $id ?>)">
                                    Leia mais...
                                </button>
                            </div>

                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <form method="POST" action="update_status.php" class="status-form">
                                    <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                    <select name="status">
                                        <option value="aberto">Aberto</option>
                                        <option value="em_andamento">Em andamento</option>
                                        <option value="fechado">Fechado</option>
                                    </select>
                                    <button type="submit">Atualizar</button>
                                </form>
                            <?php endif; ?>

                            <!-- ===== COMENTÁRIOS ===== -->
                            <div class="comments">
                                <strong>Comentários</strong>

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
                                    <textarea name="comment" placeholder="Digite um comentário..."></textarea>

                                    <label class="file-label">
                                        📎 Anexar arquivo
                                        <input type="file" name="file" hidden>
                                    </label>

                                    <button type="submit">Enviar</button>

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
                                    <strong>Anexos</strong>
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
                            <!-- ===== HISTÓRICO ===== -->
                            <div class="history">
                                <strong>Histórico</strong>
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
                                            id="btn-historico-<?= $ticket['id'] ?>">
                                            Ver histórico completo (<?= $totalHistorico - 1 ?> mais)
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <em>Sem histórico</em>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </main>

        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>

</html>