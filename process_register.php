<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['email'] ?? '';
    $contrasena = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? 'cliente'; // Por defecto cliente si no se especifica

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        echo json_encode(['success' => false, 'message' => '⚠️ Todos los campos son obligatorios.']);
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => '❌ Correo inválido.']);
        exit;
    }

    $conn = getConnection();

    // Verificar si el correo ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => '⚠️ Este correo ya está registrado.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Hashear contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol, fecha_registro)
                            VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $rol);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Usuario registrado correctamente',
            'redirect' => 'index.php'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Error al registrar el usuario. Intenta nuevamente.'
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
