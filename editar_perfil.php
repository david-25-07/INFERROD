<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: iniciar_sesion.php');
    exit;
}
require_once 'config.php';
$conn = getConnection();
$stmt = $conn->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    if ($nombre && $email) {
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $email, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $user['nombre'] = $nombre;
        $user['email'] = $email;
        $msg = "Perfil actualizado correctamente.";
    } else {
        $msg = "Todos los campos son obligatorios.";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <style>
        body{font-family:'Courier New',monospace;background:#0a0e27;color:#e0e0e0;}
        .perfil-container{max-width:500px;margin:60px auto;padding:40px;background:#1a1f3a;border-radius:20px;box-shadow:0 0 30px rgba(0,255,65,0.2);border:2px solid #00ff41;}
        h2{color:#00ff41;margin-bottom:20px;}
        .perfil-dato{margin-bottom:15px;}
        .btn{padding:12px 30px;background:#00ff41;color:#0a0e27;border:none;border-radius:8px;font-weight:bold;cursor:pointer;transition:all 0.3s;}
        .btn:hover{background:#ff6b35;color:#fff;}
        .msg{margin-bottom:20px;color:#00ff41;}
    </style>
</head>
<body>
    <div class="perfil-container">
        <h2>Editar Perfil</h2>
        <?php if(isset($msg)): ?><div class="msg"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
        <form method="post">
            <div class="perfil-dato">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
            </div>
            <div class="perfil-dato">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn">Guardar Cambios</button>
        </form>
        <form method="post" action="logout.php" style="margin-top:30px;">
            <button type="submit" class="btn">Cerrar Sesión</button>
        </form>
        <div style="margin-top:20px;"><a href="perfil.php" style="color:#00ff41;">← Volver al perfil</a></div>
    </div>
</body>
</html>
