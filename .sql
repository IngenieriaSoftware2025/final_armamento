create database armamento

INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Administrador', 'Acceso completo a todos los módulos del sistema');
INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Empleado', 'Acceso a ventas, reparaciones e inventario');
INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Técnico', 'Acceso principalmente a módulo de reparaciones');

--------------tablas originales --------------------------------------------

CREATE TABLE lopez_roles  ( 
    id_rol        	SERIAL NOT NULL,
    nombre_rol    	VARCHAR(50) NOT NULL,
    descripcion   	VARCHAR(200),
    fecha_creacion	DATETIME YEAR to SECOND DEFAULT SYSDATE YEAR to SECOND 
    )

INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Administrador', 'Acceso completo a todos los módulos del sistema');
INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Empleado', 'Acceso a ventas, reparaciones e inventario');
INSERT INTO lopez_roles (nombre_rol, descripcion) VALUES 
('Técnico', 'Acceso principalmente a módulo de reparaciones');
  

CREATE TABLE lopez_usuarios  ( 
    id_usuario     	SERIAL NOT NULL,
    nombre_usuario 	VARCHAR(50) NOT NULL,
    password       	VARCHAR(255) NOT NULL,
    nombre_completo	VARCHAR(100) NOT NULL,
    email          	VARCHAR(100),
    telefono       	VARCHAR(20),
    id_rol         	INTEGER NOT NULL,
    activo         	CHAR(1) DEFAULT 'T',
    fecha_creacion 	DATETIME YEAR to SECOND DEFAULT SYSDATE YEAR to SECOND,
    ultimo_acceso  	DATETIME YEAR to SECOND 
    );
    
 --    agregar la foto de ultimo 
   
ALTER TABLE lopez_usuarios ADD foto VARCHAR(100) NULL;

--------------------------------------------------------------------------------------

 

CREATE TABLE lopez_usuarios ( 
    id_usuario         SERIAL NOT NULL PRIMARY KEY,
    nombre_usuario     VARCHAR(50) NOT NULL,
    password           VARCHAR(255) NOT NULL,
    nombre_completo    VARCHAR(100) NOT NULL,
    email              VARCHAR(100),
    telefono           VARCHAR(20),
    id_rol             INTEGER NOT NULL,
    activo             CHAR(1) DEFAULT 'T',
    fecha_creacion     DATETIME YEAR to SECOND DEFAULT CURRENT YEAR to SECOND,
    ultimo_acceso      DATETIME YEAR to SECOND 
);
    
     
 INSERT INTO lopez_usuarios(id_usuario, nombre_usuario, password, nombre_completo, email, telefono, id_rol, activo, fecha_creacion, ultimo_acceso) 
	VALUES(1, 'paolita46', '1234567', 'Paola Lopez', 'pao@gmail.com', '57444158', 1, 'Administrador', '2025-6-20 12:19:0', '2025-6-20 12:19:0')

-- ////mi contraseña 

INSERT INTO lopez_usuarios(id_usuario, nombre_usuario, password, nombre_completo, email, telefono, id_rol, activo, fecha_creacion, ultimo_acceso, foto) 
	VALUES(1, 'paolita', '$2y$10$T61pmDFpBdNOT8D/DFF5UOh.url2yyYbNamxNBjhBFprC4fQws5Sy', 'Paola Lopez', 'pao140202@gmail.com', '57444158', 1, 'T', '2025-6-20 20:0:32', '2025-6-20 20:0:32', 'uploads/usuarios/paolita_68561405c5412.jpg')
GO
   

CREATE TABLE lopez_asignacion_marcas  ( 
    id_asignacion    	SERIAL NOT NULL,
    id_usuario       	INTEGER NOT NULL,
    id_marca         	INTEGER NOT NULL,
    fecha_asignacion 	DATETIME YEAR to SECOND DEFAULT SYSDATE YEAR to SECOND,
    activo           	CHAR(1) DEFAULT 'T',
    usuario_asignador	INTEGER,
    observaciones    	VARCHAR(255) 
    );
    
    
 CREATE TABLE lopez_marcas  ( 
    id_marca        	SERIAL NOT NULL,
    nombre_marca    	VARCHAR(50) NOT NULL,
    descripcion     	VARCHAR(200),
    activo          	CHAR(1) DEFAULT 'T',
    fecha_creacion  	DATETIME YEAR to SECOND DEFAULT SYSDATE YEAR to SECOND,
    usuario_creacion	INTEGER 
    );
    
 CREATE TABLE lopez_modelos  ( 
    id_modelo        	SERIAL NOT NULL,
    id_marca         	INTEGER NOT NULL,
    nombre_modelo    	VARCHAR(100) NOT NULL,
    especificaciones 	VARCHAR(100),
    precio_referencia	DECIMAL(10,2),
    activo           	CHAR(1) DEFAULT 'T',
    fecha_creacion   	DATETIME YEAR to SECOND DEFAULT SYSDATE YEAR to SECOND 
    );
    
 
 -----iniciar sesion
 
 CREATE TABLE usuario_login2025 (
    usu_id SERIAL PRIMARY KEY,
    usu_nombre VARCHAR(50),
    usu_codigo INTEGER,
    usu_password VARCHAR(150),
    usu_situacion SMALLINT DEFAULT 1
);

CREATE TABLE rol_login2025 (
    rol_id SERIAL PRIMARY KEY,
    rol_nombre VARCHAR(75),
    rol_nombre_ct VARCHAR(25),
    rol_situacion SMALLINT DEFAULT 1
);



CREATE TABLE permiso_login2025 (
    permiso_id SERIAL PRIMARY KEY,
    permiso_usuario INTEGER,
    permiso_rol INTEGER,
    permiso_situacion SMALLINT DEFAULT 1,
    FOREIGN KEY (permiso_usuario) REFERENCES usuario_login2025 (usu_id),
    FOREIGN KEY (permiso_rol) REFERENCES rol_login2025 (rol_id)
);




CREATE TABLE lopez_historial_actividades (
    id_actividad SERIAL NOT NULL,
    id_usuario INTEGER NOT NULL,
    tipo_actividad VARCHAR(50) NOT NULL,
    modulo VARCHAR(50),
    descripcion VARCHAR(255),
    tabla_afectada VARCHAR(50),
    id_registro_afectado INTEGER,
    ip_usuario VARCHAR(45),
    fecha_actividad DATETIME YEAR to SECOND DEFAULT SYSDATE YEAR to SECOND,
    datos_anteriores TEXT,
    datos_nuevos TEXT,
    PRIMARY KEY (id_actividad),
    FOREIGN KEY (id_usuario) REFERENCES lopez_usuarios(id_usuario)
);



INSERT INTO lopez_historial_actividades (id_usuario, tipo_actividad, modulo, descripcion, tabla_afectada, id_registro_afectado, ip_usuario, fecha_actividad) 
VALUES (2, 'ELIMINAR', 'USUARIOS', 'Usuario eliminado', 'lopez_usuarios', 2, '192.168.1.100', CURRENT);

INSERT INTO lopez_historial_actividades (id_usuario, tipo_actividad, modulo, descripcion, tabla_afectada, id_registro_afectado, ip_usuario, fecha_actividad) 
VALUES (1, 'CREAR', 'USUARIOS', 'Usuario creado', 'lopez_usuarios', 1, '192.168.1.100', CURRENT);

INSERT INTO lopez_historial_actividades (id_usuario, tipo_actividad, modulo, descripcion, tabla_afectada, id_registro_afectado, ip_usuario, fecha_actividad) 
VALUES (3, 'ASIGNACION', 'USUARIOS', 'Asignacion creada', 'lopez_usuarios', 3, '192.168.1.100', CURRENT);