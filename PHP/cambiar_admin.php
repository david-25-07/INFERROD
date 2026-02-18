<?php
require_once 'config.php';

$conn = getConnection();

$nueva_contra = 'admin123'; // Cambia a la contraseña que quieras
$hash = password_hash($nueva_contra, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE correo = ?");
$correo = 'admin@inferrod.com';
$stmt->bind_param("ss", $hash, $correo);

if ($stmt->execute()) {
    echo "Contraseña del admin actualizada correctamente.";
} else {
    echo "Error al actualizar la contraseña.";
}
?>
