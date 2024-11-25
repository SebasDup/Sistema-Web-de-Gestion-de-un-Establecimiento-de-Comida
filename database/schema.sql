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
    nombre CHAR(1) NOT NULL,
    descripcion TEXT
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
    comentarios TEXT,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE reservaciones_mesas (
    reservacion_id INT,
    mesa_id INT,
    PRIMARY KEY (reservacion_id, mesa_id),
    FOREIGN KEY (reservacion_id) REFERENCES reservaciones(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE log_meseros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT,
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
    empleado_id INT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    estado ENUM('abierta', 'cerrada') NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    comentarios TEXT,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON UPDATE CASCADE ON DELETE CASCADE
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
    hora_cierre TIME NOT NULL,
    estado ENUM('abierto', 'cerrado') DEFAULT 'abierto'
);

CREATE TABLE bitacora_acciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NULL,
    accion VARCHAR(50) NOT NULL,
    tabla VARCHAR(50) NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE historial_zonas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    zona_asignada CHAR(1) NOT NULL,
    fecha_asignacion DATE NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE bitacora_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL,
    tipo_operacion ENUM('respaldo', 'restauracion') NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    fecha_operacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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

INSERT INTO zonas (nombre, descripcion) VALUES ('A', 'Primer piso'), ('B', 'Zona Para fumar'), ('C', 'En la terraza'), ('D', 'Segundo piso'), ('E', 'Zona VIP');

INSERT INTO mesas (numero, capacidad, estado, zona_id) VALUES
(1, 4, 'disponible', 1),
(2, 6, 'disponible', 1),
(3, 2, 'disponible', 2),
(4, 8, 'disponible', 2),
(5, 4, 'disponible', 3),
(6, 6, 'disponible', 3),
(7, 2, 'disponible', 4),
(8, 8, 'disponible', 4),
(9, 4, 'disponible', 5),
(10, 6, 'disponible', 5);

INSERT INTO menu (nombre, descripcion, precio, categoria) VALUES
('Hamburguesa Clásica', 'Con queso, lechuga y tomate', 15.99, 'Plato principal'),
('Sopa de Tomate', 'Sopa casera de tomates frescos', 7.99, 'Entrada'),
('Filete de Res', 'Filete a la parrilla con guarnición', 25.99, 'Plato principal'),
('Flan de Caramelo', 'Postre tradicional de caramelo', 5.99, 'Postre'),
('Ensalada Mixta', 'Mix de lechugas con vinagreta', 8.99, 'Entrada');

INSERT INTO empleados (usuario_id, puesto, fecha_contratacion, salario, servicios_realizados, zona_asignada) VALUES
(2, 'Mesero', '2023-01-15', 1500.00, 20,'A'),
(5, 'Cocinero', '2023-02-20', 1800.00, 35,'B'),
(6, 'Cajero', '2023-03-10', 1400.00, 4,'C'),
(9, 'Mesero', '2023-04-05', 1500.00, 7,'A'),
(12, 'Cocinero', '2023-05-15', 1800.00, 21,'B');

INSERT INTO comandas (mesa_id, cliente_id, empleado_id, fecha, estado, total) VALUES
(1, 3, 1, '2024-11-18 19:30:00', 'cerrada', 150.75),
(2, 4, 2, '2024-11-19 20:00:00', 'abierta', 89.99),
(3, 7, 3, '2024-11-18 18:45:00', 'cerrada', 245.50),
(4, 8, 4, '2024-11-19 19:15:00', 'cerrada', 178.25),
(5, 10, 5, '2024-11-20 20:30:00', 'abierta', 95.80);

INSERT INTO items_comanda (comanda_id, menu_id, cantidad, precio_unitario) VALUES
(1, 1, 2, 15.99),
(1, 2, 1, 7.99),
(1, 3, 1, 25.99),
(2, 4, 1, 5.99),
(2, 5, 2, 8.99),
(3, 1, 2, 15.99),
(3, 2, 1, 7.99),
(4, 3, 3, 25.99),
(5, 4, 2, 5.99),
(5, 5, 2, 8.99);

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

INSERT INTO empleados (usuario_id, puesto, fecha_contratacion, salario, servicios_realizados, zona_asignada) VALUES
(2, 'Mesero', '2023-01-15', 1500.00, 20,'A'),
(5, 'Cocinero', '2023-02-20', 1800.00, 35,'B'),
(6, 'Cajero', '2023-03-10', 1400.00, 4,'C'),
(9, 'Mesero', '2023-04-05', 1500.00, 7,'A'),
(12, 'Cocinero', '2023-05-15', 1800.00, 21,'B'),
(13, 'Cajero', '2023-06-20', 1400.00, 17, 'C'),
(14, 'Mesero', '2023-07-10', 1500.00, 14, 'A'),
(15, 'Cocinero', '2023-08-05', 1800.00, 11,'B'),
(16, 'Cajero', '2023-09-15', 1400.00, 2, 'C'),
(17, 'Mesero', '2023-10-20', 1500.00, 53,'A'),
(18, 'Cocinero', '2023-11-10', 1800.00, 33,'B'),
(19, 'Cajero', '2023-12-05', 1400.00, 12,'C'),
(20, 'Mesero', '2024-01-15', 1500.00, 17, 'A'),
(21, 'Cocinero', '2024-02-20', 1800.00, 18, 'B'),
(22, 'Cajero', '2024-03-10', 1400.00, 22, 'C'),
(23, 'Mesero', '2024-04-05', 1500.00, 42, 'A'),
(24, 'Cocinero', '2024-05-15', 1800.00, 31, 'B'),
(25, 'Cajero', '2024-06-20', 1400.00, 12, 'C'),
(26, 'Mesero', '2024-07-10', 1500.00, 35, 'A'),
(27, 'Cocinero', '2024-08-05', 1800.00, 45, 'B'),
(28, 'Cajero', '2024-09-15', 1400.00, 24, 'C'),
(29, 'Mesero', '2024-10-20', 1500.00, 41, 'A'),
(30, 'Cocinero', '2024-11-10', 1800.00, 12, 'B'),
(31, 'Cajero', '2024-12-05', 1400.00, 15, 'C'),
(32, 'Mesero', '2025-01-15', 1500.00, 13, 'A');

INSERT INTO reservaciones (cliente_id, fecha, personas, estado, comentarios) VALUES
(3, '2024-11-18 10:00:00', 4, 'confirmada', 'Mesa junto a la ventana'),
(4, '2024-11-19 11:00:00', 2, 'pendiente', 'Mesa en el patio'),
(7, '2024-11-17 12:30:00', 6, 'confirmada', 'Mesa en el salón principal'),
(8, '2024-11-18 09:00:00', 3, 'cancelada', 'Mesa cerca de la entrada'),
(10, '2024-11-19 08:30:00', 5, 'confirmada', 'Mesa en la terraza'),
(11, '2024-11-20 10:30:00', 2, 'pendiente', 'Mesa en el salón privado'),
(13, '2024-11-19 11:00:00', 4, 'confirmada', 'Mesa junto a la barra'),
(14, '2024-11-18 12:45:00', 3, 'cancelada', 'Mesa en el jardín'),
(16, '2024-11-20 08:15:00', 2, 'confirmada', 'Mesa en el salón VIP'),
(17, '2024-11-21 09:30:00', 6, 'pendiente', 'Mesa en el salón principal');

INSERT INTO reservaciones_mesas (reservacion_id, mesa_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

DELIMITER //

CREATE TRIGGER after_empleado_insert
AFTER INSERT ON empleados
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (NEW.id, 'INSERT', 'empleados');
    INSERT INTO historial_zonas (empleado_id, zona_asignada, fecha_asignacion) VALUES (NEW.id, NEW.zona_asignada, CURDATE());
END //

CREATE TRIGGER after_empleado_update
AFTER UPDATE ON empleados
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (NEW.id, 'UPDATE', 'empleados');
    IF NEW.zona_asignada != OLD.zona_asignada THEN
        INSERT INTO historial_zonas (empleado_id, zona_asignada, fecha_asignacion) VALUES (NEW.id, NEW.zona_asignada, CURDATE());
    END IF;
END //

CREATE TRIGGER after_empleado_delete
AFTER DELETE ON empleados
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (OLD.id, 'DELETE', 'empleados');
END //

DELIMITER ;

-- Drop existing triggers for usuarios
DROP TRIGGER IF EXISTS after_usuarios_insert;
DROP TRIGGER IF EXISTS after_usuarios_update;
DROP TRIGGER IF EXISTS after_usuarios_delete;

DELIMITER //

-- Create new trigger that only logs employee actions
CREATE TRIGGER after_usuarios_insert
AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.tipo = 'empleado' OR NEW.tipo = 'administrador' THEN
        -- Get the empleado_id from empleados table if it exists
        SET @emp_id = (SELECT id FROM empleados WHERE usuario_id = NEW.id LIMIT 1);
        IF @emp_id IS NOT NULL THEN
            INSERT INTO bitacora_acciones (empleado_id, accion, tabla)
            VALUES (@emp_id, 'INSERT', 'usuarios');
        END IF;
    END IF;
END //

CREATE TRIGGER after_usuarios_update
AFTER UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF NEW.tipo = 'empleado' OR NEW.tipo = 'administrador' THEN
        SET @emp_id = (SELECT id FROM empleados WHERE usuario_id = NEW.id LIMIT 1);
        IF @emp_id IS NOT NULL THEN
            INSERT INTO bitacora_acciones (empleado_id, accion, tabla)
            VALUES (@emp_id, 'UPDATE', 'usuarios');
        END IF;
    END IF;
END //

CREATE TRIGGER after_usuarios_delete
AFTER DELETE ON usuarios
FOR EACH ROW
BEGIN
    IF OLD.tipo = 'empleado' OR OLD.tipo = 'administrador' THEN
        SET @emp_id = (SELECT id FROM empleados WHERE usuario_id = OLD.id LIMIT 1);
        IF @emp_id IS NOT NULL THEN
            INSERT INTO bitacora_acciones (empleado_id, accion, tabla)
            VALUES (@emp_id, 'DELETE', 'usuarios');
        END IF;
    END IF;
END //

DELIMITER ;

-- Drop existing mesas triggers
DROP TRIGGER IF EXISTS after_mesas_insert;
DROP TRIGGER IF EXISTS after_mesas_update;
DROP TRIGGER IF EXISTS after_mesas_delete;

DELIMITER //

-- Modified mesas triggers
CREATE TRIGGER after_mesas_insert
AFTER INSERT ON mesas
FOR EACH ROW
BEGIN
    -- Get the first admin employee id as fallback
    SET @admin_emp_id = (
        SELECT e.id 
        FROM empleados e 
        JOIN usuarios u ON e.usuario_id = u.id 
        WHERE u.tipo = 'administrador' 
        LIMIT 1
    );
    
    -- Use COALESCE to ensure we never insert NULL
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) 
    VALUES (COALESCE(@admin_emp_id, 1), 'INSERT', 'mesas');
END //

CREATE TRIGGER after_mesas_update
AFTER UPDATE ON mesas
FOR EACH ROW
BEGIN
    SET @admin_emp_id = (
        SELECT e.id 
        FROM empleados e 
        JOIN usuarios u ON e.usuario_id = u.id 
        WHERE u.tipo = 'administrador' 
        LIMIT 1
    );
    
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) 
    VALUES (COALESCE(@admin_emp_id, 1), 'UPDATE', 'mesas');
END //

CREATE TRIGGER after_mesas_delete
AFTER DELETE ON mesas
FOR EACH ROW
BEGIN
    SET @admin_emp_id = (
        SELECT e.id 
        FROM empleados e 
        JOIN usuarios u ON e.usuario_id = u.id 
        WHERE u.tipo = 'administrador' 
        LIMIT 1
    );
    
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) 
    VALUES (COALESCE(@admin_emp_id, 1), 'DELETE', 'mesas');
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_comandas_insert
AFTER INSERT ON comandas
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (NEW.id, 'INSERT', 'comandas');
END //

CREATE TRIGGER after_comandas_update
AFTER UPDATE ON comandas
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (NEW.id, 'UPDATE', 'comandas');
END //

CREATE TRIGGER after_comandas_delete
AFTER DELETE ON comandas
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (OLD.id, 'DELETE', 'comandas');
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_promociones_insert
AFTER INSERT ON promociones
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (NEW.id, 'INSERT', 'promociones');
END //

CREATE TRIGGER after_promociones_update
AFTER UPDATE ON promociones
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (NEW.id, 'UPDATE', 'promociones');
END //

CREATE TRIGGER after_promociones_delete
AFTER DELETE ON promociones
FOR EACH ROW
BEGIN
    INSERT INTO bitacora_acciones (empleado_id, accion, tabla) VALUES (OLD.id, 'DELETE', 'promociones');
END //

DELIMITER ;

SELECT *FROM usuarios;
select *from reservaciones;
select *From reservaciones_mesas;
SELECT *FROM MENU;
select *from bitacora_db;
select *from zonas;
select *from mesas;
