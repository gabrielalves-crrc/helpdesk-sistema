<?php
require 'auth.php';

if ($_SESSION['role'] !== 'user') {
    die('Acesso negado');
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<?php
// DEFINA AS VARIÁVEIS PRIMEIRO
$pageTitle = "Abrir Novo Chamado - Sistema de Suporte HelpDesk CRRC Brasil";
$pageDescription = "Abra um novo chamado técnico no sistema HelpDesk da CRRC Brasil. Solicite suporte para TI, manutenção industrial, ferramentaria, almoxarifado ou outros departamentos. Preencha o formulário detalhado para atendimento rápido.";
$pageKeywords = "Abrir chamado técnico, suporte CRRC, solicitar manutenção, problema TI, assistência técnica, helpdesk online, formulário chamado, suporte ferroviário";
$ogImage = "uploads/logotipo.png";

// AGORA INCLUA O HEAD
include 'assets/head/head.php';
?>

<body>
    <div class="app">
        <!-- SIDEBAR -->
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
                    <a href="dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i>首页 / Home</a>

                    <?php if ($_SESSION['role'] === 'user'): ?>
                        <a href="novo_chamado.php" class="menu-item active"><i class="fa-solid fa-plus"></i>新呼叫 / Novo Chamado</a>
                    <?php else: ?>
                        <a href="itens-enviados.php" class="menu-item"><i class="fa-solid fa-address-book"></i>发送 / Enviados</a>
                        <a href="lixeira.php" class="menu-item"><i class="fa-solid fa-trash"></i>垃圾桶 / Lixeira</a>
                        <a href="admin_create_user.php" class="menu-item"><i class="fa-solid fa-user-plus"></i>创建用户 / Criar Usuário</a>
                    <?php endif; ?>
                </nav>
                <!-- <div class="flex-icon-dark">
                <button id="toggleDark" class="dark-btn">🌙</button>
            </div> -->
            </div>
        </aside>

        <!-- CONTEÚDO -->
        <div class="main">

            <!-- TOPO -->
            <div class="topbar">
                <div class="logo">
                    <img src="uploads/logotipo-att.jpeg" alt="Logo" class="logo-img">
                </div>

            </div>

            <!-- CONTEÚDO DA PÁGINA -->
            <div class="dashboard-call">
                <div class="form-box">
                    <h2>新呼叫 / Novo Chamado</h2>

                    <form method="POST" action="salvar_chamado.php">
                        <label>标题 / Título</label>
                        <input type="text" name="titulo" required>

                        <label>描述 / Descrição</label>
                        <textarea name="descricao" rows="5" required></textarea>

                        <button type="submit" class="btn-primary">提交工单 / Abrir Chamado</button>
                        <a href="dashboard.php" class="btn-secondary">取消 / Cancelar</a>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- JS DO TOGGLE -->
    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
    </script>
    <!-- Botão Voltar ao Topo -->
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">
        ↑
    </button>

    <script src="assets/js/script.js"></script>
</body>

</html>