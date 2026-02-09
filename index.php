<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<?php
$pageTitle = "Login - Acesso ao Sistema | HelpDesk CRRC";
$pageDescription = "Faça login no sistema de helpdesk da CRRC Brasil com suas credenciais corporativas.";
$pageKeywords = "login, acesso, autenticação, helpdesk CRRC";
$ogImage = "uploads/logotipo.png";

include 'assets/head/head.php';
?>

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
        <p class="subtitle">系统访问权限 / Acesso ao sistema</p>

        <?php if (isset($_GET['erro'])): ?>
            <div class="error">Usuário ou senha inválidos</div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>用户 / Usuário</label>
            <input type="text" name="username" required>

            <label>密码 / Senha</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn-primary">进入 / Entrar</button>
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