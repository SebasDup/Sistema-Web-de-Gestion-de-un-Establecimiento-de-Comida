DROP DATABASE IF EXISTS restaurante_db;
CREATE DATABASE restaurante_db;
USE restaurante_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(35) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(30) NOT NULL,
    tipo VARCHAR(15) NOT NULL
);

CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    puesto VARCHAR(50) NOT NULL,
    fecha_contratacion DATE NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    zona_asignada VARCHAR(50),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE zonas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    capacidad INT NOT NULL,
    estado ENUM('disponible', 'ocupada', 'reservada') NOT NULL,
    zona_id INT,
    FOREIGN KEY (zona_id) REFERENCES zonas(id)
);

CREATE TABLE reservaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    fecha DATETIME NOT NULL,
    personas INT NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id)
);

CREATE TABLE reservaciones_mesas (
    reservacion_id INT,
    mesa_id INT,
    PRIMARY KEY (reservacion_id, mesa_id),
    FOREIGN KEY (reservacion_id) REFERENCES reservaciones(id),
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    categoria VARCHAR(50) NOT NULL
);

CREATE TABLE comandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mesa_id INT,
    cliente_id INT,
    fecha DATETIME NOT NULL,
    estado ENUM('abierta', 'cerrada') NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id)
);

CREATE TABLE items_comanda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comanda_id INT,
    menu_id INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (comanda_id) REFERENCES comandas(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

CREATE TABLE promociones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    descuento DECIMAL(5, 2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL
);

INSERT INTO usuarios (usuario, email, contrasena, tipo) VALUES
('Admin', 'admin@restaurante.com', 'admin123', 'administrador'),
('Mesero', 'mesero@restaurante.com', 'mesero123', 'empleado'),
('Cliente', 'cliente@email.com', 'cliente123', 'cliente');

INSERT INTO zonas (nombre) VALUES ('Zona A'), ('Zona B'), ('Zona C');

INSERT INTO mesas (numero, capacidad, estado, zona_id) VALUES
(1, 4, 'disponible', 1),
(2, 6, 'disponible', 1),
(3, 2, 'disponible', 2),
(4, 8, 'disponible', 2),
(5, 4, 'disponible', 3);

INSERT INTO menu (nombre, descripcion, precio, categoria) VALUES
('Pasta Carbonara', 'Pasta con salsa cremosa y panceta', 12.99, 'Plato principal'),
('Ensalada César', 'Lechuga romana con aderezo César y crutones', 8.99, 'Entrada'),
('Tiramisú', 'Postre italiano con café y mascarpone', 6.99, 'Postre');