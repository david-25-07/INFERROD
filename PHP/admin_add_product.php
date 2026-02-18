<?php
require_once 'config.php';
session_start();
header('Content-Type: application/json');

// Solo admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success'=>false, 'message'=>'Acceso denegado']);
    exit;
}

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nombre = $_POST['nombre'] ?? null;
    $correo = $_POST['correo'] ?? null;
    $password = $_POST['password'] ?? null;
    $rol = $_POST['rol'] ?? 'cliente';

    if(!$nombre || !$correo || !$password){
        echo json_encode(['success'=>false,'message'=>'Todos los campos son obligatorios']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $conn = getConnection();

    // Verificar que el correo no exista
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE correo=?");
    $stmt->bind_param("s",$correo);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
        echo json_encode(['success'=>false,'message'=>'Correo ya registrado']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $nombre, $correo, $hashed_password, $rol);
    if($stmt->execute()){
        echo json_encode(['success'=>true,'message'=>'Usuario agregado correctamente']);
    } else {
        echo json_encode(['success'=>false,'message'=>'Error al agregar usuario']);
    }
} else {
    echo json_encode(['success'=>false,'message'=>'Método no permitido']);
}
?>
