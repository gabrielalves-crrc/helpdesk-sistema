<?php
require 'auth.php';

if ($_SESSION['role'] !== 'user') {
    die('Acesso negado');
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

        <!-- CONTEÚDO -->
        <div class="main">

            <!-- TOPO -->
            <div class="topbar">
                <div class="logo">Novo Chamado</div>
                <div class="top-actions">
                    <a href="logout.php" class="btn-logout">Sair</a>
                </div>
            </div>

            <!-- CONTEÚDO DA PÁGINA -->
            <div class="dashboard">
                <div class="form-box">
                    <h2>Novo Chamado</h2>

                    <form method="POST" action="salvar_chamado.php">
                        <label>Título</label>
                        <input type="text" name="titulo" required>

                        <label>Descrição</label>
                        <textarea name="descricao" rows="5" required></textarea>

                        <button type="submit" class="btn-primary">Abrir Chamado</button>
                        <a href="dashboard.php" class="btn-secondary">Cancelar</a>
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