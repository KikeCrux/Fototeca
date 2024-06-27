CREATE DATABASE Fototeca_OB_UAA;
USE fototeca_ob_uaa;

-- Seccion de usuarios y logs
CREATE TABLE Usuarios (
    ID_Usuario INT PRIMARY KEY AUTO_INCREMENT,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Contraseña VARCHAR(100) NOT NULL,
    TipoUsuario ENUM('Admin', 'Arte') NOT NULL
);

CREATE TABLE UserLogs (
    ID_Log INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    FechaHora DATETIME NOT NULL,
    IP VARCHAR(50),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);


-- Tabla Resguardante
CREATE TABLE Resguardante (
    ID_Resguardante INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(80) NOT NULL,
    PuestoDepartamento VARCHAR(35),
    Observaciones TEXT
);

-- Tabla Asignado
CREATE TABLE Asignado (
    ID_Asignado INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(80) NOT NULL,
    PuestoDepartamento VARCHAR(35),
    Observaciones TEXT
);

-- Tabla DatosGenerales
CREATE TABLE DatosGenerales (
    ID_DatosGenerales INT AUTO_INCREMENT PRIMARY KEY,
    Autores VARCHAR(100),
    ObjetoObra VARCHAR(50),
    Ubicacion VARCHAR(100),
    NoInventario VARCHAR(20),
    NoVale VARCHAR(20),
    FechaPrestamo DATE,
    Caracteristicas TEXT,
    Observaciones TEXT,
    ImagenOficioVale MEDIUMBLOB -- BLOB para almacenar imágenes
    ImagenObra MEDIUMBLOB -- BLOB para almacenar imágenes
    ID_Asignado fK de la tabla Personal y PK ID_Personal en la tabla Personal,
    ID_Resguardante fK de la tabla Personaly PK ID_Personal en la tabla Personal,
    TipoObra VARCHAR(30)
);

-- Desarrollador
INSERT INTO Usuarios (Usuario, Contraseña, TipoUsuario) 
VALUES ('usuario_prueba', 'contraseña_prueba', 'Arte');

SELECT * FROM Asignado;

ALTER TABLE Asignado
DROP COLUMN Puesto,
DROP COLUMN Departamento;

ALTER TABLE Asignado
ADD COLUMN PuestoDepartamento VARCHAR(35),
ADD COLUMN Observaciones TEXT;

