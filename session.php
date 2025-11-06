<?php
// Manejo de sesión seguro
if(session_status() === PHP_SESSION_NONE){
    session_start();
    session_regenerate_id(true); // Evita conflictos y secuestro de sesión
}

// Variables de sesión generales
$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['user_name']);
$user_name   = $_SESSION['user_name'] ?? 'Invitado';
$user_email  = $_SESSION['user_email'] ?? '';
$user_role   = $_SESSION['user_role'] ?? 'Invitado';
?>
