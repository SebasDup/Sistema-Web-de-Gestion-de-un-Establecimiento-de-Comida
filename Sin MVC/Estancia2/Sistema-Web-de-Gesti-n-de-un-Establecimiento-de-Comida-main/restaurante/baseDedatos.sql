DROP DATABASE IF EXISTS restaurante_db;
CREATE DATABASE restaurante_db;
USE restaurante_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(35) NOT NULL,
    apellido VARCHAR(35) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(30) NOT NULL,
    tipo VARCHAR(16) NOT NULL
);

CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    puesto VARCHAR(50) NOT NULL,
    fecha_contratacion DATE NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    servicios_realizados INT DEFAULT 0,
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
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    zona_id INT,
    FOREIGN KEY (zona_id) REFERENCES zonas(id)
);

CREATE TABLE reservaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT, 
    fecha DATETIME NOT NULL,
    personas INT NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') NOT NULL,
    total_comida DECIMAL(10, 2),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id)
);

CREATE TABLE reservaciones_mesas (
    reservacion_id INT,
    mesa_id INT,
    ganancia DECIMAL(10, 2),
    PRIMARY KEY (reservacion_id, mesa_id),
    FOREIGN KEY (reservacion_id) REFERENCES reservaciones(id),
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

CREATE TABLE log_meseros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    mesa_id INT NOT NULL,
    fecha_servicio DATETIME NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id),
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
    total_servicios INT,
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

CREATE TABLE horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia_semana ENUM('lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo') NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL
);

INSERT INTO usuarios (nombre,  apellido,email, contrasena, tipo) VALUES
('Admin', 'Perez','admin@restaurante.com', 'admin123', 'administrador'),
('Mesero', 'Lopez','mesero@restaurante.com', 'mesero123', 'empleado'),
('Cliente', 'Hernandez','cliente@email.com', 'cliente123', 'cliente');

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

INSERT INTO promociones (titulo, descripcion, descuento, fecha_inicio, fecha_fin) VALUES
('2x1 en bebidas', 'Promoción de 2x1 en bebidas alcohólicas', 50.00, '2024-06-01', '2025-06-30'),
('Descuento del 20% en postres', 'Obtén un 20% de descuento en todos los postres', 20.00, '2024-07-01', '2025-07-31'),
('Happy Hour', 'Descuento del 30% en bebidas de 5pm a 7pm', 30.00, '2024-08-01', '2025-08-31'),
('Menú del día', 'Descuento del 15% en el menú del día', 15.00, '2024-09-01', '2025-09-30'),
('Cena para dos', 'Cena para dos personas por el precio de una', 50.00, '2024-10-01', '2025-10-31'),
('Descuento del 10% en todas las comidas', 'Obtén un 10% de descuento en todas las comidas del menú', 10.00, '2024-11-01', '2025-11-30');

INSERT INTO horarios (dia_semana, hora_apertura, hora_cierre) VALUES
('lunes', '10:00:00', '20:00:00'),
('martes', '12:00:00', '20:00:00'),
('miércoles', '10:00:00', '20:00:00'),
('jueves', '11:00:00', '20:00:00'),
('viernes', '10:00:00', '20:00:00'),
('sábado', '11:00:00', '20:00:00'),
('domingo', '10:00:00', '19:00:00');

INSERT INTO empleados (usuario_id, puesto, fecha_contratacion, salario, zona_asignada) VALUES
(2, 'Mesero', '2021-01-15', 1500.00, 'Zona A'),
(2, 'Mesero', '2021-02-20', 1500.00, 'Zona B'),
(2, 'Mesero', '2021-03-10', 1500.00, 'Zona C'),
(2, 'Mesero', '2021-04-05', 1500.00, 'Zona A'),
(2, 'Mesero', '2021-05-25', 1500.00, 'Zona B');