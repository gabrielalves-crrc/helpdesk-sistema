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
    <title>Novo Chamado</title>
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
        <aside class="sidebar">
            <div class="sidebar-header">
                <span class="logo-text">HelpDesk</span>
                <button class="toggle-btn">☰</button>
            </div>

            <nav class="menu">
                <a href="dashboard.php">
                    <span class="text">Dashboard</span>
                </a>
                <a href="novo_chamado.php" class="active">
                    <span class="text">Novo Chamado</span>
                </a>
            </nav>
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