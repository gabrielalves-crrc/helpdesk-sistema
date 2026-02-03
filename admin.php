<?php
require 'auth.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: chamados.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Administração</title>
</head>

<body>

    <h2>Administração</h2>
    <p>Configurações do sistema aqui.</p>

</body>

</html>