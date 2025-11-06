<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Si la petición es AJAX (fetch) o POST, responde JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'Sesión expirada', 'redirect'=>'iniciar_sesion.php']);
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'Sesión expirada', 'redirect'=>'iniciar_sesion.php']);
        exit();
    }
    header('Location: iniciar_sesion.php');
    exit;
}
require_once 'config.php';
$conn = getConnection();
if (!$conn) {
    die('<div style="color:red;text-align:center;">Error de conexión a la base de datos.</div>');
}
$stmt = $conn->prepare("SELECT id, nombre, email, fecha_registro, ultimo_acceso FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <style>
        body{font-family:'Courier New',monospace;background:#0a0e27;color:#e0e0e0;}
        .perfil-container{max-width:500px;margin:60px auto;padding:40px;background:#1a1f3a;border-radius:20px;box-shadow:0 0 30px rgba(0,255,65,0.2);border:2px solid #00ff41;}
        h2{color:#00ff41;margin-bottom:20px;}
        .perfil-dato{margin-bottom:15px;}
        .btn{padding:12px 30px;background:#00ff41;color:#0a0e27;border:none;border-radius:8px;font-weight:bold;cursor:pointer;transition:all 0.3s;}
        .btn:hover{background:#ff6b35;color:#fff;}
    </style>
</head>
<body>
    <div class="perfil-container">
        <h2>Perfil de Usuario</h2>
        <?php if($user): ?>
            <div class="perfil-dato"><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></div>
            <div class="perfil-dato"><strong>Nombre:</strong> <?php echo htmlspecialchars($user['nombre']); ?></div>
            <div class="perfil-dato"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
            <div class="perfil-dato"><strong>Fecha de registro:</strong> <?php echo htmlspecialchars($user['fecha_registro']); ?></div>
            <div class="perfil-dato"><strong>Último acceso:</strong> <?php echo htmlspecialchars($user['ultimo_acceso']); ?></div>
            <div class="perfil-dato" style="margin-top:20px;">
                <a href="editar_perfil.php" class="btn" style="background:transparent; border:2px solid #00ff41; color:#00ff41;">✏️ Modificar Perfil</a>
            </div>
            <form method="post" action="logout.php" style="margin-top:30px;">
                <button type="submit" class="btn">Cerrar Sesión</button>
            </form>
        <?php else: ?>
            <p>No se encontró información del usuario.</p>
        <?php endif; ?>
    </div>
</body>
</html>
