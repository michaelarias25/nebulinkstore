-- Crear base de datos
CREATE DATABASE IF NOT EXISTS nebulink_store
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE nebulink_store;

-- Crear tabla de postulaciones
CREATE TABLE IF NOT EXISTS postulaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    estado VARCHAR(50) NOT NULL DEFAULT 'aceptado',
    fecha_postulacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
