DROP DATABASE IF EXISTS restaurante_db;
CREATE DATABASE restaurante_db;
USE restaurante_db;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(35) NOT NULL,
    apellidoP VARCHAR(35) NOT NULL,
    apellidoM VARCHAR(35) NOT NULL,
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
    zona_asignada CHAR(1),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE zonas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre CHAR(1) NOT NULL
);

CREATE TABLE mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL,
    capacidad INT NOT NULL,
    estado ENUM('disponible', 'ocupada', 'reservada') NOT NULL,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    zona_id INT,
    FOREIGN KEY (zona_id) REFERENCES zonas(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE reservaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    fecha DATETIME NOT NULL,
    personas INT NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') NOT NULL,
    total_comida DECIMAL(10, 2),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE reservaciones_mesas (
    reservacion_id INT,
    mesa_id INT,
    ganancia DECIMAL(10, 2),
    PRIMARY KEY (reservacion_id, mesa_id),
    FOREIGN KEY (reservacion_id) REFERENCES reservaciones(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE log_meseros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    mesa_id INT NOT NULL,
    fecha_servicio DATETIME NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON UPDATE CASCADE ON DELETE CASCADE
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
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE items_comanda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comanda_id INT,
    menu_id INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (comanda_id) REFERENCES comandas(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON UPDATE CASCADE ON DELETE CASCADE
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

INSERT INTO usuarios (nombre,  apellidoP, apellidoM, email, contrasena, tipo) VALUES
('Admin', 'Herrera','Perez','admin@restaurante.com', 'admin123', 'administrador'),
('Mesero', 'Jaimez','Lopez','mesero@restaurante.com', 'mesero123', 'empleado'),
('Cliente', 'Ruiz','Hernandez','cliente@email.com', 'cliente123', 'cliente'),
('Juan', 'Reyes', 'Flores', 'jure@gmail.com', 'juan123', 'cliente'),
('Maria', 'Gomez', 'Sanchez', 'maria@restaurante.com', 'maria123', 'empleado'),
('Carlos', 'Diaz', 'Martinez', 'carlos@restaurante.com', 'carlos123', 'empleado'),
('Luis', 'Fernandez', 'Gomez', 'luis@restaurante.com', 'luis123', 'cliente'),
('Ana', 'Martinez', 'Lopez', 'ana@restaurante.com', 'ana123', 'cliente'),
('Pedro', 'Sanchez', 'Diaz', 'pedro@restaurante.com', 'pedro123', 'empleado'),
('Laura', 'Hernandez', 'Garcia', 'laura@restaurante.com', 'laura123', 'cliente'),
('Miguel', 'Torres', 'Martinez', 'miguel@restaurante.com', 'miguel123', 'cliente'),
('Sofia', 'Lopez', 'Martinez', 'sofia@restaurante.com', 'sofia123', 'empleado'),
('Roberto', 'Mendez', 'Gonzalez', 'roberto@restaurante.com', 'roberto123', 'cliente'),
('Elena', 'Ramirez', 'Lopez', 'elena@restaurante.com', 'elena123', 'cliente'),
('Fernando', 'Castro', 'Martinez', 'fernando@restaurante.com', 'fernando123', 'cliente'),
('Isabel', 'Ortiz', 'Hernandez', 'isabel@restaurante.com', 'isabel123', 'cliente'),
('Jorge', 'Vargas', 'Sanchez', 'jorge@restaurante.com', 'jorge123', 'cliente'),
('Patricia', 'Morales', 'Diaz', 'patricia@restaurante.com', 'patricia123', 'cliente'),
('Raul', 'Gutierrez', 'Garcia', 'raul@restaurante.com', 'raul123', 'cliente'),
('Silvia', 'Rojas', 'Perez', 'silvia@restaurante.com', 'silvia123', 'cliente'),
('Victor', 'Navarro', 'Jimenez', 'victor@restaurante.com', 'victor123', 'cliente'),
('Yolanda', 'Molina', 'Ruiz', 'yolanda@restaurante.com', 'yolanda123', 'cliente'),
('Andrea', 'Santos', 'Gomez', 'andrea@restaurante.com', 'andrea123', 'empleado'),
('Bruno', 'Herrera', 'Lopez', 'bruno@restaurante.com', 'bruno123', 'empleado'),
('Claudia', 'Cruz', 'Martinez', 'claudia@restaurante.com', 'claudia123', 'empleado'),
('Daniel', 'Flores', 'Hernandez', 'daniel@restaurante.com', 'daniel123', 'empleado'),
('Esteban', 'Garcia', 'Sanchez', 'esteban@restaurante.com', 'esteban123', 'empleado'),
('Fabiola', 'Martinez', 'Diaz', 'fabiola@restaurante.com', 'fabiola123', 'empleado'),
('Gabriel', 'Lopez', 'Garcia', 'gabriel@restaurante.com', 'gabriel123', 'empleado'),
('Hector', 'Perez', 'Martinez', 'hector@restaurante.com', 'hector123', 'empleado'),
('Irene', 'Gonzalez', 'Hernandez', 'irene@restaurante.com', 'irene123', 'empleado'),
('Julio', 'Ramirez', 'Sanchez', 'julio@restaurante.com', 'julio123', 'empleado');

INSERT INTO zonas (nombre) VALUES ('A'), ('B'), ('C');

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
(2, 'Mesero', '2023-01-15', 1500.00, 'A'),
(5, 'Cocinero', '2023-02-20', 1800.00, 'B'),
(6, 'Cajero', '2023-03-10', 1400.00, 'C'),
(9, 'Mesero', '2023-04-05', 1500.00, 'A'),
(12, 'Cocinero', '2023-05-15', 1800.00, 'B'),
(13, 'Cajero', '2023-06-20', 1400.00, 'C'),
(14, 'Mesero', '2023-07-10', 1500.00, 'A'),
(15, 'Cocinero', '2023-08-05', 1800.00, 'B'),
(16, 'Cajero', '2023-09-15', 1400.00, 'C'),
(17, 'Mesero', '2023-10-20', 1500.00, 'A'),
(18, 'Cocinero', '2023-11-10', 1800.00, 'B'),
(19, 'Cajero', '2023-12-05', 1400.00, 'C'),
(20, 'Mesero', '2024-01-15', 1500.00, 'A'),
(21, 'Cocinero', '2024-02-20', 1800.00, 'B'),
(22, 'Cajero', '2024-03-10', 1400.00, 'C'),
(23, 'Mesero', '2024-04-05', 1500.00, 'A'),
(24, 'Cocinero', '2024-05-15', 1800.00, 'B'),
(25, 'Cajero', '2024-06-20', 1400.00, 'C'),
(26, 'Mesero', '2024-07-10', 1500.00, 'A'),
(27, 'Cocinero', '2024-08-05', 1800.00, 'B'),
(28, 'Cajero', '2024-09-15', 1400.00, 'C'),
(29, 'Mesero', '2024-10-20', 1500.00, 'A'),
(30, 'Cocinero', '2024-11-10', 1800.00, 'B'),
(31, 'Cajero', '2024-12-05', 1400.00, 'C'),
(32, 'Mesero', '2025-01-15', 1500.00, 'A');