/* -----------------------------------------------------------
   BASE DE DATOS
----------------------------------------------------------- */
CREATE DATABASE IF NOT EXISTS venta_autos
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE venta_autos;

/* -----------------------------------------------------------
   TABLAS DE APOYO
----------------------------------------------------------- */
CREATE TABLE tipoUsuario (
    id_tipo_us     INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo    VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

/* -----------------------------------------------------------
   USUARIOS Y PERSONAS
----------------------------------------------------------- */
CREATE TABLE usuarios (
    correoElectronico  VARCHAR(100) PRIMARY KEY,
    nombreUsuario      VARCHAR(50)  NOT NULL UNIQUE,
    password           VARCHAR(255) NOT NULL,     -- almacena el hash
    id_tipo_us         INT          NOT NULL,
    vigente            BOOLEAN      NOT NULL DEFAULT TRUE,
    CONSTRAINT fk_usuario_tipo
      FOREIGN KEY (id_tipo_us) REFERENCES tipoUsuario(id_tipo_us)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE personas (
    dni               BIGINT       PRIMARY KEY,
    nombre            VARCHAR(50)  NOT NULL,
    apellido          VARCHAR(50)  NOT NULL,
    direccion         VARCHAR(100) NOT NULL,
    localidad         VARCHAR(50)  NOT NULL,
    provincia         VARCHAR(50)  NOT NULL,
    cp                VARCHAR(10)  NOT NULL,
    correoElectronico VARCHAR(100) NOT NULL,
    CONSTRAINT fk_persona_usuario
      FOREIGN KEY (correoElectronico) REFERENCES usuarios(correoElectronico)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

/* -----------------------------------------------------------
   MAESTROS DE VEHÍCULOS
----------------------------------------------------------- */
CREATE TABLE marcas (
    id_marca  INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE modelos (
    id_modelo INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50) NOT NULL,
    version   VARCHAR(50),
    id_marca  INT         NOT NULL,
    CONSTRAINT fk_modelo_marca
      FOREIGN KEY (id_marca) REFERENCES marcas(id_marca)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    UNIQUE(nombre, version, id_marca)              -- evita duplicados exactos
) ENGINE=InnoDB;

CREATE TABLE combustible (
    id_combustible INT AUTO_INCREMENT PRIMARY KEY,
    combustible    VARCHAR(30) NOT NULL UNIQUE,
    descripcion    VARCHAR(100)
) ENGINE=InnoDB;

/* -----------------------------------------------------------
   VEHÍCULOS Y FOTOS
----------------------------------------------------------- */
CREATE TABLE vehiculos (
    dominio          VARCHAR(10)  PRIMARY KEY,     -- patente
    id_modelo        INT          NOT NULL,
    anio             YEAR         NOT NULL,
    precio           DECIMAL(12,2) NOT NULL,
    reservado        BOOLEAN      NOT NULL DEFAULT FALSE,
    color            VARCHAR(30),
    id_combustible   INT          NOT NULL,
    kilometraje      INT UNSIGNED,
    descripcion      VARCHAR(255),
    disponible       BOOLEAN      NOT NULL DEFAULT TRUE,
    correoElectronico VARCHAR(100),                -- quién cargó el auto (opcional)
    CONSTRAINT fk_vehiculo_modelo
      FOREIGN KEY (id_modelo)      REFERENCES modelos(id_modelo)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_vehiculo_combustible
      FOREIGN KEY (id_combustible) REFERENCES combustible(id_combustible)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_vehiculo_usuario
      FOREIGN KEY (correoElectronico) REFERENCES usuarios(correoElectronico)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE fotos (
    id_foto   INT AUTO_INCREMENT PRIMARY KEY,
    dominio   VARCHAR(10)  NOT NULL,
    foto      VARCHAR(255) NOT NULL,   -- ruta o nombre de archivo
    CONSTRAINT fk_foto_vehiculo
      FOREIGN KEY (dominio) REFERENCES vehiculos(dominio)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

/* -----------------------------------------------------------
   PUBLICACIONES
----------------------------------------------------------- */
CREATE TABLE publicaciones (
    id_publicacion    INT AUTO_INCREMENT PRIMARY KEY,
    fecha             DATE         NOT NULL,
    tipo              ENUM('oportunidad','ocasión','destacada','regular') NOT NULL,
    dominio           VARCHAR(10)  NOT NULL,
    vigente           BOOLEAN      NOT NULL DEFAULT TRUE,
    descripcion       VARCHAR(255),
    correoElectronico VARCHAR(100) NOT NULL,   -- usuario que publica
    CONSTRAINT fk_pub_vehiculo
      FOREIGN KEY (dominio) REFERENCES vehiculos(dominio)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_pub_usuario
      FOREIGN KEY (correoElectronico) REFERENCES usuarios(correoElectronico)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

/* -----------------------------------------------------------
   ÍNDICES ÚTILES
----------------------------------------------------------- */
CREATE INDEX idx_vehiculos_precio      ON vehiculos(precio);
CREATE INDEX idx_vehiculos_kilometraje ON vehiculos(kilometraje);
CREATE INDEX idx_publicaciones_tipo    ON publicaciones(tipo, vigente);
CREATE INDEX idx_fotos_dominio         ON fotos(dominio);
