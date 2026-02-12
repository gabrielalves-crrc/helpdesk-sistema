<?php
if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'usuario_excluido') {
    $mensagem = "Usuário excluído permanentemente!";
}

if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'auto_delete') {
        $erro = 'Você não pode excluir seu próprio usuário!';
    } elseif ($_GET['erro'] === 'tem_chamados') {
        $id = $_GET['id'] ?? '';
        $erro = "Usuário #$id possui chamados ativos. Transfira ou feche os chamados primeiro!";
    } elseif ($_GET['erro'] === 'banco') {
        $erro = 'Erro ao excluir usuário. Tente novamente.';
    } elseif ($_GET['erro'] === 'invalido') {
        $erro = 'ID de usuário inválido.';
    }
}

require 'auth.php';
require 'config.php';

// SÓ ADMIN PODE ACESSAR
if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php?erro=acesso_negado');
    exit;
}

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (empty($username) || empty($password)) {
        $erro = 'Usuário e senha são obrigatórios!';
    } elseif (strlen($password) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            $erro = 'Usuário já existe! Escolha outro nome.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $hash, $role]);

            $mensagem = "Usuário <strong>$username</strong> criado com sucesso!";
            $_POST = [];
        }
    }
}
?>

<?php
$pageTitle = "Criar Usuário - Admin | HelpDesk CRRC";
include 'assets/head/head.php';
?>

<style>
    /* ===== CSS ORGANIZADO ===== */
</style>

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
                        <a href="admin_create_user.php" class="menu-item active"><i class="fa-solid fa-user-plus"></i>创建用户 / Criar Usuário</a>
                    <?php endif; ?>
                </nav>
            </div>
        </aside>

        <!-- CONTEÚDO -->
        <div class="main">
            <header class="topbar">
                <div class="logo">
                    <img src="uploads/logotipo-att.jpeg" alt="Logo" class="logo-img">
                </div>
                <div class="admin-badge">
                    Painel Administrativo
                </div>
            </header>

            <div class="banner"></div>

            <main class="dashboard">
                <div class="create-user-container">
                    <!-- CABEÇALHO -->
                    <div class="page-header">
                        <h2>创建用户 / Criar Novo Usuário</h2>
                        <a href="dashboard.php" class="btn-back">
                            ← Voltar
                        </a>
                    </div>

                    <!-- MENSAGENS -->
                    <?php if ($mensagem): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($erro): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i> <?= $erro ?>
                        </div>
                    <?php endif; ?>

                    <!-- FORMULÁRIO -->
                    <div class="form-card">
                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">
                                    用户名 / Nome de usuário
                                </label>
                                <input type="text"
                                    name="username"
                                    class="form-control"
                                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                    required
                                    placeholder="Ex: joao.silva">
                                <span class="form-help">
                                    Será usado para login (máx. 50 caracteres)
                                </span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    密码 / Senha
                                </label>
                                <div class="password-wrapper">
                                    <input type="text"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        value="<?= htmlspecialchars($_POST['password'] ?? '') ?>"
                                        required
                                        placeholder="Digite ou gere uma senha">
                                    <button type="button"
                                        onclick="gerarSenha()"
                                        class="btn-generate">
                                        Gerar
                                    </button>
                                </div>
                                <span class="form-help">
                                    Mínimo 6 caracteres. Sugestão: Nome@ano (Ex: Joao@2026)
                                </span>
                                <div id="senhaForte" class="password-strong">
                                    <span>✅ Senha forte gerada!</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    角色 / Nível de acesso
                                </label>
                                <select name="role" class="form-select">
                                    <option value="user" selected>Usuário Comum</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                <span class="form-help">
                                    Admin: acesso total | Usuário: só abre e acompanha chamados
                                </span>
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn-primary2">
                                    创建用户 / Criar Usuário
                                </button>
                                <button type="reset" class="btn-reset">
                                    Limpar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ÚLTIMOS USUÁRIOS CRIADOS -->
                    <?php
                    $ultimos = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY id DESC LIMIT 5");
                    $usuarios = $ultimos->fetchAll();
                    ?>

                    <?php if ($usuarios): ?>
                        <div class="users-table-section">
                            <h3>
                                <i class="fas fa-history"></i>
                                Últimos usuários cadastrados
                            </h3>
                            <div class="table-responsive">
                                <table class="users-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Usuário</th>
                                            <th>Nível</th>
                                            <th>Criado em</th>
                                            <th style="text-align: center;">Ações</th> <!-- NOVA COLUNA -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $u): ?>
                                            <tr>
                                                <td>#<?= $u['id'] ?></td>
                                                <td>
                                                    <?= htmlspecialchars($u['username']) ?>
                                                    <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                                        <span style="font-size: 12px; color: #1e3a8a; margin-left: 5px;">(você)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($u['role'] === 'admin'): ?>
                                                        <span class="badge-admin">Admin</span>
                                                    <?php else: ?>
                                                        <span class="badge-user">Usuário</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($u['created_at'] ?? 'now')) ?></td>
                                                <td style="text-align: center;">
                                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                                        <!-- NÃO PODE EXCLUIR A SI MESMO -->
                                                        <a href="delete_user.php?id=<?= $u['id'] ?>"
                                                            class="btn-delete-user"
                                                            onclick="return confirm('⚠️ TEM CERTEZA?\n\nDeseja excluir PERMANENTEMENTE o usuário \" <?= htmlspecialchars($u['username']) ?>\"?\n\nTodos os chamados deste usuário serão transferidos para o administrador padrão.\n\nEsta ação NÃO pode ser desfeita!');">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span style="color: #ccc; cursor: not-allowed;">
                                                            <i class="fas fa-ban"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-users fa-3x"></i>
                            <p>Nenhum usuário cadastrado ainda.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">↑</button>

    <script>
        function gerarSenha() {
            const nomes = ['Admin', 'User', 'Suporte', 'Tecnico', 'Operador', 'Gestor', 'Analista'];
            const ano = new Date().getFullYear();
            const nome = nomes[Math.floor(Math.random() * nomes.length)];
            const numero = Math.floor(Math.random() * 100);
            const senha = nome + '@' + ano + numero;

            document.getElementById('password').value = senha;
            document.getElementById('senhaForte').style.display = 'block';

            setTimeout(() => {
                document.getElementById('senhaForte').style.display = 'none';
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const usernameField = document.querySelector('input[name="username"]');
            if (usernameField) usernameField.focus();
        });
    </script>
</body>

</html>