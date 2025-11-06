<?php
require_once 'config.php';
session_start();

// Solo permitir acceso a registro si el usuario es admin o si no hay sesión (para nuevos clientes)
$rolUsuario = $_SESSION['user_role'] ?? 'cliente';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? 'cliente';

    // Si no es admin, forzar rol cliente
    if ($rolUsuario !== 'admin') {
        $rol = 'cliente';
    }

    // Validar campos
    if (empty($nombre) || empty($correo) || empty($password)) {
        $_SESSION['register_error'] = "Todos los campos son obligatorios.";
        header("Location: register_form.php");
        exit;
    }

    $conn = getConnection();

    // Verificar si el correo ya está registrado
    $check = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $check->bind_param("s", $correo);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['register_error'] = "Este correo ya está registrado.";
        header("Location: register_form.php");
        exit;
    }

    // Insertar nuevo usuario
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $correo, $hashedPassword, $rol);

    if ($stmt->execute()) {
        $_SESSION['register_success'] = "Usuario registrado exitosamente.";
        header("Location: login_form.php");
        exit;
    } else {
        $_SESSION['register_error'] = "Error al registrar el usuario.";
        header("Location: register_form.php");
        exit;
    }
}
?>
