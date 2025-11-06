-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2025 a las 14:22:52
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inferrod`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `tabla` varchar(50) NOT NULL,
  `accion` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `id_registro` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `tabla`, `accion`, `id_registro`, `descripcion`, `fecha`) VALUES
(1, 'usuarios', 'INSERT', 10, 'Usuario creado: Administrador', '2025-10-28 05:38:25'),
(2, 'usuarios', 'INSERT', 11, 'Usuario creado: Vendedor', '2025-10-28 05:38:25'),
(3, 'usuarios', 'INSERT', 12, 'Usuario creado: Cliente de Prueba', '2025-10-28 05:38:25'),
(4, 'usuarios', 'UPDATE', 1, 'Usuario actualizado: Administrador', '2025-10-28 05:40:14'),
(5, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 05:40:21'),
(6, 'usuarios', 'UPDATE', 3, 'Usuario actualizado: Cliente de Prueba', '2025-10-28 05:40:29'),
(7, 'usuarios', 'INSERT', 13, 'Usuario creado: JHON DAVID DELGADO', '2025-10-28 05:59:42'),
(8, 'usuarios', 'INSERT', 14, 'Usuario creado: pepesierra', '2025-10-28 06:36:12'),
(9, 'usuarios', 'DELETE', 13, 'Usuario eliminado: JHON DAVID DELGADO', '2025-10-28 06:44:49'),
(10, 'usuarios', 'INSERT', 15, 'Usuario creado: sergio forero', '2025-10-28 06:45:28'),
(11, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 06:54:26'),
(12, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:38:22'),
(13, 'productos', 'UPDATE', 22, NULL, '2025-10-28 07:38:22'),
(14, 'productos', 'UPDATE', 24, NULL, '2025-10-28 07:38:22'),
(15, 'productos', 'UPDATE', 26, NULL, '2025-10-28 07:38:22'),
(16, 'productos', 'UPDATE', 27, NULL, '2025-10-28 07:38:22'),
(17, 'productos', 'UPDATE', 29, NULL, '2025-10-28 07:38:22'),
(18, 'productos', 'UPDATE', 31, NULL, '2025-10-28 07:38:22'),
(19, 'productos', 'UPDATE', 32, NULL, '2025-10-28 07:38:22'),
(20, 'productos', 'UPDATE', 34, NULL, '2025-10-28 07:38:22'),
(21, 'productos', 'UPDATE', 36, NULL, '2025-10-28 07:38:22'),
(22, 'productos', 'UPDATE', 37, NULL, '2025-10-28 07:38:22'),
(23, 'productos', 'UPDATE', 39, NULL, '2025-10-28 07:38:22'),
(24, 'productos', 'UPDATE', 23, NULL, '2025-10-28 07:38:22'),
(25, 'productos', 'UPDATE', 25, NULL, '2025-10-28 07:38:22'),
(26, 'productos', 'UPDATE', 28, NULL, '2025-10-28 07:38:22'),
(27, 'productos', 'UPDATE', 30, NULL, '2025-10-28 07:38:22'),
(28, 'productos', 'UPDATE', 33, NULL, '2025-10-28 07:38:22'),
(29, 'productos', 'UPDATE', 35, NULL, '2025-10-28 07:38:22'),
(30, 'productos', 'UPDATE', 38, NULL, '2025-10-28 07:38:22'),
(31, 'productos', 'UPDATE', 40, NULL, '2025-10-28 07:38:22'),
(32, 'productos', 'INSERT', 41, NULL, '2025-10-28 07:38:42'),
(33, 'productos', 'INSERT', 42, NULL, '2025-10-28 07:38:42'),
(34, 'productos', 'INSERT', 43, NULL, '2025-10-28 07:38:42'),
(35, 'productos', 'INSERT', 44, NULL, '2025-10-28 07:38:42'),
(36, 'productos', 'INSERT', 45, NULL, '2025-10-28 07:38:42'),
(37, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:39:06'),
(38, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:40:34'),
(39, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:41:35'),
(40, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:41:39'),
(41, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:41:55'),
(42, 'productos', 'UPDATE', 21, NULL, '2025-10-28 07:42:02'),
(43, 'usuarios', 'UPDATE', 1, 'Usuario actualizado: Administrador', '2025-10-28 07:52:22'),
(44, 'usuarios', 'UPDATE', 1, 'Usuario actualizado: Administrador', '2025-10-28 07:52:56'),
(45, 'usuarios', 'UPDATE', 1, 'Usuario actualizado: Administrador', '2025-10-28 07:55:46'),
(46, 'usuarios', 'UPDATE', 1, 'Usuario actualizado: Administrador', '2025-10-28 07:56:34'),
(47, 'usuarios', 'INSERT', 17, 'Usuario creado: santiago', '2025-10-28 10:49:00'),
(48, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:45'),
(49, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:46'),
(50, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:47'),
(51, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:47'),
(52, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:47'),
(53, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:48'),
(54, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:48'),
(55, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:48'),
(56, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:57:48'),
(57, 'usuarios', 'UPDATE', 2, 'Usuario actualizado: Vendedor', '2025-10-28 10:58:20'),
(58, 'productos', 'UPDATE', 26, NULL, '2025-10-28 10:59:12'),
(59, 'productos', 'UPDATE', 22, NULL, '2025-10-28 10:59:46'),
(60, 'usuarios', 'INSERT', 18, 'Usuario creado: santiago millan', '2025-10-28 11:04:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `detalle_venta`
--
DELIMITER $$
CREATE TRIGGER `after_delete_detalle` AFTER DELETE ON `detalle_venta` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla, accion, id_registro, descripcion)
    VALUES ('detalle_venta', 'DELETE', OLD.id_detalle, CONCAT('Detalle eliminado de venta ID ', OLD.id_venta));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_detalle` BEFORE INSERT ON `detalle_venta` FOR EACH ROW BEGIN
    SET NEW.subtotal = NEW.cantidad * NEW.precio_unitario;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nombre_producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `categoria` varchar(50) DEFAULT 'General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `precio`, `stock`, `fecha_registro`, `nombre_producto`, `cantidad`, `disponible`, `categoria`) VALUES
(21, 25000.00, 10, '2025-10-28 07:12:57', 'MARTILLO', 10, 0, 'Herramientas'),
(22, 15000.00, 5, '2025-10-28 07:12:57', 'DESTORNILLADOR', 5, 0, 'Herramientas'),
(23, 8000.00, 20, '2025-10-28 07:12:57', 'CLAVOS 1KG', 20, 1, 'Ferretería'),
(24, 120000.00, 2, '2025-10-28 07:12:57', 'TALADRO', 2, 1, 'Herramientas'),
(25, 9000.00, 15, '2025-10-28 07:12:57', 'TORNILLOS 1KG', 15, 1, 'Ferretería'),
(26, 25000.00, 10, '2025-10-28 07:13:12', 'MARTILLO', 10, 0, 'Herramientas'),
(27, 15000.00, 5, '2025-10-28 07:13:12', 'DESTORNILLADOR', 5, 1, 'Herramientas'),
(28, 8000.00, 20, '2025-10-28 07:13:12', 'CLAVOS 1KG', 20, 1, 'Ferretería'),
(29, 120000.00, 2, '2025-10-28 07:13:12', 'TALADRO', 2, 1, 'Herramientas'),
(30, 9000.00, 15, '2025-10-28 07:13:12', 'TORNILLOS 1KG', 15, 1, 'Ferretería'),
(31, 25000.00, 10, '2025-10-28 07:13:20', 'MARTILLO', 10, 1, 'Herramientas'),
(32, 15000.00, 5, '2025-10-28 07:13:20', 'DESTORNILLADOR', 5, 1, 'Herramientas'),
(33, 8000.00, 20, '2025-10-28 07:13:20', 'CLAVOS 1KG', 20, 1, 'Ferretería'),
(34, 120000.00, 2, '2025-10-28 07:13:20', 'TALADRO', 2, 1, 'Herramientas'),
(35, 9000.00, 15, '2025-10-28 07:13:20', 'TORNILLOS 1KG', 15, 1, 'Ferretería'),
(36, 25000.00, 10, '2025-10-28 07:13:44', 'MARTILLO', 10, 1, 'Herramientas'),
(37, 15000.00, 5, '2025-10-28 07:13:44', 'DESTORNILLADOR', 5, 1, 'Herramientas'),
(38, 8000.00, 20, '2025-10-28 07:13:44', 'CLAVOS 1KG', 20, 1, 'Ferretería'),
(39, 120000.00, 2, '2025-10-28 07:13:44', 'TALADRO', 2, 1, 'Herramientas'),
(40, 9000.00, 15, '2025-10-28 07:13:44', 'TORNILLOS 1KG', 15, 1, 'Ferretería'),
(41, 25000.00, 10, '2025-10-28 07:38:42', 'MARTILLO', 10, 1, 'Herramientas'),
(42, 15000.00, 5, '2025-10-28 07:38:42', 'DESTORNILLADOR', 5, 1, 'Herramientas'),
(43, 8000.00, 20, '2025-10-28 07:38:42', 'CLAVOS 1KG', 20, 1, 'Ferretería'),
(44, 120000.00, 2, '2025-10-28 07:38:42', 'TALADRO', 2, 1, 'Herramientas'),
(45, 9000.00, 15, '2025-10-28 07:38:42', 'TORNILLOS 1KG', 15, 1, 'Ferretería');

--
-- Disparadores `productos`
--
DELIMITER $$
CREATE TRIGGER `after_delete_producto` AFTER DELETE ON `productos` FOR EACH ROW INSERT INTO auditoria (tabla, accion, id_registro, fecha)
VALUES ('productos', 'DELETE', OLD.id_producto, NOW())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_producto` AFTER INSERT ON `productos` FOR EACH ROW INSERT INTO auditoria (tabla, accion, id_registro, fecha)
VALUES ('productos', 'INSERT', NEW.id_producto, NOW())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_producto` AFTER UPDATE ON `productos` FOR EACH ROW INSERT INTO auditoria (tabla, accion, id_registro, fecha)
VALUES ('productos', 'UPDATE', NEW.id_producto, NOW())
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_producto` BEFORE INSERT ON `productos` FOR EACH ROW SET NEW.nombre_producto = UPPER(NEW.nombre_producto)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `nombre_trigger` BEFORE INSERT ON `productos` FOR EACH ROW SET NEW.nombre_producto = UPPER(NEW.nombre_producto)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','vendedor','cliente') NOT NULL DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `correo`, `contrasena`, `rol`, `fecha_registro`, `force_password_change`) VALUES
(1, 'Administrador', 'admin@inferrod.com', '$2y$10$Rw1EZWDMHLl9UYMKY3jUF.CPpxp9tATzeE10OTXi7zs3DpVGJsF7C', 'admin', '2025-10-28 05:38:25', 0),
(2, 'Vendedor', 'vendedor@inferrod.com', '$2y$10$xaiJU5DbU/TXjKTsAT0Vbe.FjpmLcnHWWgsOoBA8x8rkFgtdZUDom', 'vendedor', '2025-10-28 05:38:25', 0),
(3, 'Cliente de Prueba', 'cliente@inferrod.com', '$2y$10$wSxYgdyHL.TyHpk4KzBqz.9u3m9QvYyl.mfbiD8W2jZ0tv6aXlGUa', 'cliente', '2025-10-28 05:38:25', 0),
(14, 'pepesierra', 'test@inferrod.com', '$2y$10$nHNchpaug6j5ipMbrk/2mu0LyMSSeXIlyx1UcPF8I67l8Bh2/Jaxm', 'cliente', '2025-10-28 06:36:12', 0),
(15, 'sergio forero', 'jhonddelgador@juandelcorral.edu.co', '$2y$10$pUL8h7O5bI8EHYsuIHroCOO00j/a1S5u2eFhrhVsCgupRHzgrBxo.', 'cliente', '2025-10-28 06:45:28', 0),
(17, 'santiago', 'santiago@gmail.com', '$2y$10$36kl2PVuTtw9lJ8DscthkurOiOoZEFd6rvLRQE98Vum6Ms.ZXdeeq', 'cliente', '2025-10-28 10:49:00', 0),
(18, 'santiago millan', 'santiago202501@gmail.com', '$2y$10$8AJLG0H6zy1k50lZSLZvvumdFS3nqH8I0gQCrp4Bmpk6zrpNjDPEe', 'cliente', '2025-10-28 11:04:54', 0);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `after_delete_usuario` AFTER DELETE ON `usuarios` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla, accion, id_registro, descripcion)
    VALUES ('usuarios', 'DELETE', OLD.id_usuario, CONCAT('Usuario eliminado: ', OLD.nombre_usuario));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_usuario` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla, accion, id_registro, descripcion, fecha)
    VALUES ('usuarios', 'INSERT', NEW.id_usuario, CONCAT('Usuario creado: ', NEW.nombre_usuario), NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_usuario` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla, accion, id_registro, descripcion)
    VALUES ('usuarios', 'UPDATE', NEW.id_usuario, CONCAT('Usuario actualizado: ', NEW.nombre_usuario));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_usuario` BEFORE INSERT ON `usuarios` FOR EACH ROW BEGIN
    SET NEW.fecha_registro = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','otro') DEFAULT 'efectivo',
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `ventas`
--
DELIMITER $$
CREATE TRIGGER `after_delete_venta` AFTER DELETE ON `ventas` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla, accion, id_registro, descripcion)
    VALUES ('ventas', 'DELETE', OLD.id_venta, 'Venta eliminada del sistema');
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_venta` AFTER INSERT ON `ventas` FOR EACH ROW BEGIN
    INSERT INTO auditoria (tabla, accion, id_registro, descripcion, fecha)
    VALUES ('ventas', 'INSERT', NEW.id_venta, CONCAT('Venta creada por usuario ', NEW.id_usuario), NOW());
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_venta` BEFORE INSERT ON `ventas` FOR EACH ROW BEGIN
    SET NEW.fecha_venta = NOW();
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `correo_2` (`correo`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
