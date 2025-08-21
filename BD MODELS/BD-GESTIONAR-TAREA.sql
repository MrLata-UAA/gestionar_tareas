-- CREAR LA BASE DE DATOS
CREATE DATABASE IF NOT EXISTS gestionar_tareas;

-- USAR LA BD CREADA
USE gestionar_tareas;

-- Tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasenha VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
);

-- Tabla tareas
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    estado ENUM('pendiente','en progreso','completada') DEFAULT 'pendiente',
    fecha_inicio DATE DEFAULT (CURRENT_DATE),
    fecha_limite DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Usuario de prueba
INSERT INTO usuarios (nombre, email, contrasenha) 
VALUES ('Usuario Demo', 'test@demo.com', 
'$2y$10$KIXpFqXFF8z14PavNPtOD.h9GzqO3of8xFs.7lRM3wW7hVzL0qI8W'); 
-- Contraseña = 123456

-- Tareas de prueba
INSERT INTO tareas (usuario_id, titulo, descripcion, estado, fecha_inicio, fecha_limite) VALUES
(1, 'Comprar víveres', 'Leche, pan, frutas y verduras', 'completada','2025-08-19' , '2025-08-25'),
(1, 'Estudiar PHP', 'Repasar CRUD y sesiones', 'en progreso', '2025-08-20', '2025-08-25'),
(1, 'Enviar proyecto', 'Subir repositorio a GitHub', 'pendiente', '2025-08-21', '2025-08-25');
