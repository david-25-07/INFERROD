<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados desde JS
    $correo   = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validar que no estén vacíos
    if (empty($correo) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Debes ingresar correo y contraseña.']);
        exit;
    }

    // Conexión a la base de datos
    $conn = getConnection();
    if (!$conn) {
        echo json_encode(['success'=>false, 'message'=>'Error de conexión a la base de datos.']);
        exit;
    }

    // Preparar consulta usando nombres de columnas correctos
    $stmt = $conn->prepare("SELECT id_usuario, nombre_usuario, rol, contrasena FROM usuarios WHERE correo = ?");
    if (!$stmt) {
        echo json_encode(['success'=>false, 'message'=>'Error en la consulta: '.$conn->error]);
        exit;
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si existe el usuario
    if ($user = $result->fetch_assoc()) {
        // Verificar contraseña hasheada
        if (password_verify($password, $user['contrasena'])) {
            // Guardar sesión
            $_SESSION['user_id']   = $user['id_usuario'];
            $_SESSION['user_name'] = $user['nombre_usuario'];
            $_SESSION['user_role'] = $user['rol'];

            echo json_encode([
                'success' => true,
                'message' => 'Login correcto',
                'role'    => $user['rol']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Si alguien accede por GET
echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
