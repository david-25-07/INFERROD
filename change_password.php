<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit;
}

$conn = getConnection();
$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['pass1'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';

    if (strlen($pass1) < 8) {
        $errors[] = "La contrasena debe tener al menos 8 caracteres.";
    }
    if ($pass1 !== $pass2) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (empty($errors)) {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ?, force_password_change = 0 WHERE id_usuario = ?");
        $stmt->bind_param("si", $hash, $user_id);
        if ($stmt->execute()) {
            $success = "Contrasena actualizada correctamente.";
            // Redirigir según rol
            $role = $_SESSION['user_role'] ?? 'cliente';
            switch ($role) {
                case 'admin': header("Location: admin_panel.php"); break;
                case 'vendedor': header("Location: seller_panel.php"); break;
                default: header("Location: index.php"); break;
            }
            exit;
        } else {
            $errors[] = "Error al guardar la contraseña. Intenta de nuevo.";
        }
    }
}
?>
<!-- Formulario simple (colócalo donde el usuario lo vea) -->
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Cambiar contraseña</title></head>
<body>
    <h2>Cambiar contraseña</h2>
    <?php foreach ($errors as $e): ?>
        <p style="color:red;"><?php echo htmlspecialchars($e); ?></p>
    <?php endforeach; ?>
    <?php if ($success): ?>
        <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Nueva contraseña:<br><input type="password" name="pass1" required></label><br>
        <label>Confirmar contraseña:<br><input type="password" name="pass2" required></label><br>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
