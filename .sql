
-- 1. TABLA DE ROLES DE USUARIOS
CREATE TABLE lopez_roles (
    id_rol SERIAL PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(200),
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND
);

INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Administrador', 'Acceso completo a todos los módulos del sistema');
INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Empleado', 'Acceso a ventas, reparaciones e inventario');
INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Técnico', 'Acceso principalmente a módulo de reparaciones');
drop table lopez_usuarios


-- 2. TABLA DE USUARIOS


CREATE TABLE lopez_usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(20),
    id_rol INT NOT NULL,
    activo CHAR(1) DEFAULT 'T',
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    ultimo_acceso DATETIME YEAR TO SECOND,
    FOREIGN KEY (id_rol) REFERENCES lopez_roles(id_rol)
);


-- 3. TABLA DE MARCAS DE CELULARES

CREATE TABLE lopez_marcas (
    id_marca SERIAL PRIMARY KEY,
    nombre_marca VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(200),
    activo CHAR(1) DEFAULT "T",
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    usuario_creacion INT,
    FOREIGN KEY (usuario_creacion) REFERENCES lopez_usuarios(id_usuario)
);

CREATE TABLE lopez_modelos (
    id_modelo SERIAL PRIMARY KEY,
    id_marca INT NOT NULL,
    nombre_modelo VARCHAR(100) NOT NULL,
    especificaciones VARCHAR(100),
    precio_referencia DECIMAL(10,2),
    activo CHAR(1) DEFAULT 'T',
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    FOREIGN KEY (id_marca) REFERENCES lopez_marcas(id_marca),
    UNIQUE (id_marca, nombre_modelo)
);


-- TABLA DE ASIGNACIONES DE ARMAMENTO A USUARIOS
CREATE TABLE lopez_asignaciones_armamento (
    id_asignacion SERIAL PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_modelo INT NOT NULL,
    numero_serie VARCHAR(100),
    fecha_asignacion DATE NOT NULL,
    fecha_devolucion DATE,
    estado VARCHAR(20) DEFAULT 'ASIGNADO', -- ASIGNADO, DEVUELTO, PERDIDO, DAÑADO
    observaciones VARCHAR(500),
    activo CHAR(1) DEFAULT 'T',
    fecha_creacion DATETIME YEAR TO SECOND DEFAULT CURRENT YEAR TO SECOND,
    usuario_creacion INT,
    FOREIGN KEY (id_usuario) REFERENCES lopez_usuarios(id_usuario),
    FOREIGN KEY (id_modelo) REFERENCES lopez_modelos(id_modelo),
    FOREIGN KEY (usuario_creacion) REFERENCES lopez_usuarios(id_usuario),
    UNIQUE (numero_serie, activo) -- Evita duplicar números de serie activos
);
