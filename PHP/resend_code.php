<?php
require_once 'config.php';
require_once 'send_email.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email es obligatorio']);
        exit;
    }
    
    $conn = getConnection();
    
    // Buscar usuario
    $stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE email = ? AND verificado = 0");
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
    
    // Generar nuevo código
    $token = sprintf("%06d", mt_rand(1, 999999));
    $expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    // Actualizar token
    $stmt = $conn->prepare("UPDATE usuarios SET token_verificacion = ?, token_expira = ? WHERE id = ?");
    $stmt->bind_param("ssi", $token, $expira, $user['id']);
    
    if ($stmt->execute()) {
        // Enviar email
        if (sendVerificationEmail($email, $user['nombre'], $token)) {
            echo json_encode(['success' => true, 'message' => 'Código reenviado a tu email']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Tu nuevo código es: ' . $token]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al generar nuevo código']);
    }
    
    $stmt->close();
    $conn->close();
}
?>