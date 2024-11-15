-- Respaldo de la base de datos restaurante_db
-- Fecha: 2024-11-15 20:01:14

DROP DATABASE IF EXISTS `restaurante_db`;
CREATE DATABASE `restaurante_db`;
USE `restaurante_db`;

SET FOREIGN_KEY_CHECKS=0;



CREATE TABLE `bitacora_db` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(100) DEFAULT NULL,
  `tipo_operacion` enum('respaldo','restauracion') NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `fecha_operacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `bitacora_db_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bitacora_db` VALUES(1,NULL,'Sistema','respaldo','backup_2024-11-15_19-54-19.sql','2024-11-15 12:54:19');
INSERT INTO `bitacora_db` VALUES(2,NULL,'Sistema','restauracion','backup_2024-11-15_19-54-19.sql','2024-11-15 12:54:51');
INSERT INTO `bitacora_db` VALUES(3,1,'Administrador','respaldo','backup_2024-11-15_20-01-14.sql','2024-11-15 13:01:14');


CREATE TABLE `comandas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mesa_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `estado` enum('abierta','cerrada') NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `total_servicios` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mesa_id` (`mesa_id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `comandas_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  CONSTRAINT `comandas_ibfk_2` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `puesto` varchar(50) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `servicios_realizados` int(11) DEFAULT 0,
  `zona_asignada` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `empleados` VALUES(1,2,'Mesero','2021-01-15',1500.00,0,'Zona A');
INSERT INTO `empleados` VALUES(2,2,'Mesero','2021-02-20',1500.00,0,'Zona B');
INSERT INTO `empleados` VALUES(3,2,'Mesero','2021-03-10',1500.00,0,'Zona C');
INSERT INTO `empleados` VALUES(4,2,'Mesero','2021-04-05',1500.00,0,'Zona A');
INSERT INTO `empleados` VALUES(5,2,'Mesero','2021-05-25',1500.00,0,'Zona B');


CREATE TABLE `horarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia_semana` enum('lunes','martes','miércoles','jueves','viernes','sábado','domingo') NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `horarios` VALUES(1,'lunes','10:00:00','20:00:00');
INSERT INTO `horarios` VALUES(2,'martes','12:00:00','20:00:00');
INSERT INTO `horarios` VALUES(3,'miércoles','10:00:00','20:00:00');
INSERT INTO `horarios` VALUES(4,'jueves','11:00:00','20:00:00');
INSERT INTO `horarios` VALUES(5,'viernes','10:00:00','20:00:00');
INSERT INTO `horarios` VALUES(6,'sábado','11:00:00','20:00:00');
INSERT INTO `horarios` VALUES(7,'domingo','10:00:00','19:00:00');


CREATE TABLE `items_comanda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comanda_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comanda_id` (`comanda_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `items_comanda_ibfk_1` FOREIGN KEY (`comanda_id`) REFERENCES `comandas` (`id`),
  CONSTRAINT `items_comanda_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `log_meseros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empleado_id` int(11) NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `fecha_servicio` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `empleado_id` (`empleado_id`),
  KEY `mesa_id` (`mesa_id`),
  CONSTRAINT `log_meseros_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`),
  CONSTRAINT `log_meseros_ibfk_2` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `menu` VALUES(1,'Pasta Carbonara','Pasta con salsa cremosa y panceta',12.99,'Plato principal');
INSERT INTO `menu` VALUES(2,'Ensalada César','Lechuga romana con aderezo César y crutones',8.99,'Entrada');
INSERT INTO `menu` VALUES(3,'Tiramisú','Postre italiano con café y mascarpone',6.99,'Postre');


CREATE TABLE `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `estado` enum('disponible','ocupada','reservada') NOT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `zona_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zona_id` (`zona_id`),
  CONSTRAINT `mesas_ibfk_1` FOREIGN KEY (`zona_id`) REFERENCES `zonas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `mesas` VALUES(1,1,4,'disponible','2024-11-15 12:51:42',1);
INSERT INTO `mesas` VALUES(2,2,6,'disponible','2024-11-15 12:51:42',1);
INSERT INTO `mesas` VALUES(3,3,2,'disponible','2024-11-15 12:51:42',2);
INSERT INTO `mesas` VALUES(4,4,8,'disponible','2024-11-15 12:51:42',2);
INSERT INTO `mesas` VALUES(5,5,4,'disponible','2024-11-15 12:51:42',3);


CREATE TABLE `promociones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `descuento` decimal(5,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `promociones` VALUES(1,'2x1 en bebidas','Promoción de 2x1 en bebidas alcohólicas',50.00,'2024-06-01','2025-06-30');
INSERT INTO `promociones` VALUES(2,'Descuento del 20% en postres','Obtén un 20% de descuento en todos los postres',20.00,'2024-07-01','2025-07-31');
INSERT INTO `promociones` VALUES(3,'Happy Hour','Descuento del 30% en bebidas de 5pm a 7pm',30.00,'2024-08-01','2025-08-31');
INSERT INTO `promociones` VALUES(4,'Menú del día','Descuento del 15% en el menú del día',15.00,'2024-09-01','2025-09-30');
INSERT INTO `promociones` VALUES(5,'Cena para dos','Cena para dos personas por el precio de una',50.00,'2024-10-01','2025-10-31');
INSERT INTO `promociones` VALUES(6,'Descuento del 10% en todas las comidas','Obtén un 10% de descuento en todas las comidas del menú',10.00,'2024-11-01','2025-11-30');


CREATE TABLE `reservaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `personas` int(11) NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') NOT NULL,
  `total_comida` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `reservaciones_mesas` (
  `reservacion_id` int(11) NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `ganancia` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`reservacion_id`,`mesa_id`),
  KEY `mesa_id` (`mesa_id`),
  CONSTRAINT `reservaciones_mesas_ibfk_1` FOREIGN KEY (`reservacion_id`) REFERENCES `reservaciones` (`id`),
  CONSTRAINT `reservaciones_mesas_ibfk_2` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(35) NOT NULL,
  `apellido` varchar(35) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(30) NOT NULL,
  `tipo` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` VALUES(1,'Admin','Perez','admin@restaurante.com','admin123','administrador');
INSERT INTO `usuarios` VALUES(2,'Mesero','Lopez','mesero@restaurante.com','mesero123','empleado');
INSERT INTO `usuarios` VALUES(3,'Cliente','Hernandez','cliente@email.com','cliente123','cliente');


CREATE TABLE `zonas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `zonas` VALUES(1,'Zona A');
INSERT INTO `zonas` VALUES(2,'Zona B');
INSERT INTO `zonas` VALUES(3,'Zona C');


SET FOREIGN_KEY_CHECKS=1;