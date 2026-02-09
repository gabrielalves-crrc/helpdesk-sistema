<?php
require 'auth.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<?php include 'assets/head/head.php' ?>

<body>

    <h2>Administração</h2>
    <p>Configurações do sistema aqui.</p>
    <!-- Botão Voltar ao Topo -->
    <button id="backToTop" class="back-to-top" title="Voltar ao topo">
        ↑
    </button>
    <script src="assets/js/script.js"></script>
</body>

</html>