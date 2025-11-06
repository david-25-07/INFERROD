-- Crear base de datos
CREATE DATABASE IF NOT EXISTS techhardware CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techhardware;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verificado TINYINT(1) DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    token_verificacion VARCHAR(10) NULL,
    token_expira DATETIME NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME NULL,
    INDEX idx_email (email),
    INDEX idx_activo (activo),
    INDEX idx_verificado (verificado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario de prueba (ya verificado)
-- Email: test@test.com
-- Password: 123456
INSERT INTO usuarios (nombre, email, password, verificado, activo, fecha_registro) VALUES 
('Usuario de Prueba', 'test@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1, NOW())
ON DUPLICATE KEY UPDATE nombre = nombre;