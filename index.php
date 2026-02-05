<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - HelpDesk</title>
    <link href="https://fonts.googleapis.com/css2?family=Monoton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
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

<body class="login-page">

    <!-- ===== VÍDEO DE FUNDO ===== -->
    <div class="video-background">
        <video autoplay muted loop playsinline id="bgVideo">
            <!-- Formatos para compatibilidade -->
            <source src="uploads/video/video-att1.mp4" type="video/mp4">
            <source src="uploads/trem-bg.webm" type="video/webm">
            <!-- Fallback para navegadores antigos -->
            <img src="uploads/logotipo-att.jpeg" alt="Fundo CRRC">
        </video>
        <!-- Overlay escuro para melhor contraste -->
        <div class="video-overlay"></div>
    </div>

    <!-- ===== CARD DE LOGIN ===== -->
    <div class="login-card">
        <img src="uploads/logotipo-att.jpeg" alt="Logo CRRC">
        <h1>HelpDesk</h1>
        <p class="subtitle">Acesso ao sistema</p>

        <?php if (isset($_GET['erro'])): ?>
            <div class="error">Usuário ou senha inválidos</div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>Usuário</label>
            <input type="text" name="username" required>

            <label>Senha</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn-primary">Entrar</button>
        </form>

        <div class="login-card-footer">
            © <?= date('Y') ?> HelpDesk
        </div>
    </div>

    <!-- ===== CONTROLE DE SOM (opcional) ===== -->
    <button id="muteBtn" class="mute-btn" title="Ativar/Desativar som">
        🔇
    </button>

    <script>
        // Controle de mute do vídeo
        document.getElementById('muteBtn').addEventListener('click', function() {
            var video = document.getElementById('bgVideo');
            video.muted = !video.muted;
            this.textContent = video.muted ? '🔇' : '🔊';
        });

        // Fallback se vídeo não carregar
        document.getElementById('bgVideo').addEventListener('error', function() {
            document.querySelector('.video-background').style.background = "url('uploads/logotipo-att.jpeg') center/cover no-repeat";
        });
    </script>

    <script src="assets/js/script.js"></script>
</body>

</html>