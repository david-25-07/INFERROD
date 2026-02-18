<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $code = $_POST['code'] ?? '';
    
    if (empty($email) || empty($code)) {
        echo json_encode(['success' => false, 'message' => 'Email y código son obligatorios']);
        exit;
    }
    
    $conn = getConnection();
    
    // Buscar usuario con el token
    $stmt = $conn->prepare("SELECT id, token_verificacion, token_expira FROM usuarios WHERE email = ? AND verificado = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado o ya verificado']);
        $stmt->close();
        $conn->close();
        exit;
    }
    
    $user = $result->fetch_assoc();
    
    // Verificar si el token expiró
    if (strtotime($user['token_expira']) < time()) {
        echo json_encode(['success' => false, 'message' => 'El código ha expirado. Solicita uno nuevo.']);
        $stmt->close();
        $conn->close();
        exit;
    }
    
    // Verificar el código
    if ($user['token_verificacion'] !== $code) {
        echo json_encode(['success' => false, 'message' => 'Código incorrecto']);
        $stmt->close();
        $conn->close();
        exit;
    }
    
    // Marcar como verificado
    $stmt = $conn->prepare("UPDATE usuarios SET verificado = 1, token_verificacion = NULL, token_expira = NULL WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '¡Cuenta verificada exitosamente!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al verificar la cuenta']);
    }
    
    $stmt->close();
    $conn->close();
}
?>