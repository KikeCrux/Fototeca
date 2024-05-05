CREATE DATABASE Fototeca_UAA;
USE fototeca_uaa;

-- Seccion de usuarios y logs
CREATE TABLE Usuarios (
    ID_Usuario INT PRIMARY KEY AUTO_INCREMENT,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Contraseña VARCHAR(100) NOT NULL,
    TipoUsuario ENUM('Admin', 'General') NOT NULL
);

CREATE TABLE UserLogs ( 
    ID_Log INT PRIMARY KEY AUTO_INCREMENT,
    ID_Usuario INT,
    FechaHora DATETIME NOT NULL,
    IP VARCHAR(50),
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID_Usuario)
);

-- Tabla SeccionTecnica
CREATE TABLE SeccionTecnica (
    ID_Tecnica INT PRIMARY KEY AUTO_INCREMENT,
    NumeroInventario VARCHAR(50) NOT NULL,
    ClaveTecnica VARCHAR(50) NOT NULL,
    ProcesoFotografico VARCHAR(100),
    FondoColeccion VARCHAR(100),
    Formato VARCHAR(50),
    NumeroNegativoCopia VARCHAR(50),
    Tipo VARCHAR(50)
);

-- Tabla Clave
CREATE TABLE Clave (
    ID_Cultural INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Datacion
CREATE TABLE Datacion (
    ID_Datacion INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    FechaAsunto DATE,
    FechaToma DATE,
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica)O N DELETE CASCADE
);

-- Tabla UbicacionGeografica
CREATE TABLE UbicacionGeografica (
    ID_Ubicacion INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    LugarAsunto VARCHAR(100),
    LugarToma VARCHAR(100),
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Epocario
CREATE TABLE Epocario (
    ID_Epoca INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    Epoca VARCHAR(100),
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Autoria
CREATE TABLE Autoria (
    ID_Autoria INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    Autor VARCHAR(100),
    AutorPrimigenio VARCHAR(100),
    AgenciaEstudio VARCHAR(100),
    EditorColeccionista VARCHAR(100),
    Lema VARCHAR(100),
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Indicativo
CREATE TABLE Indicativo (
    ID_Indicativo INT PRIMARY KEY AUTO_INCREMENT,
    ID_Autoria INT,
    Sello VARCHAR(100),
    Cuño VARCHAR(100),
    Firma VARCHAR(100),
    Etiqueta VARCHAR(100),
    Imprenta VARCHAR(100),
    Otro VARCHAR(100),
    FOREIGN KEY (ID_Autoria) REFERENCES Autoria(ID_Autoria) ON DELETE CASCADE
);

-- Tabla Denominacion
CREATE TABLE Denominacion (
    ID_Denominacion INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    TituloOrigen VARCHAR(255),
    TituloCatalografico VARCHAR(255),
    TituloSerie VARCHAR(255),
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Descriptores
CREATE TABLE Descriptores (
    ID_Descriptores INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    TemaPrincipal VARCHAR(255),
    Descriptores TEXT,
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Protagonistas
CREATE TABLE Protagonistas (
    ID_Protagonistas INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    Personajes TEXT,
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Tabla Observaciones
CREATE TABLE Observaciones (
    ID_Observaciones INT PRIMARY KEY AUTO_INCREMENT,
    ID_Tecnica INT,
    InscripcionOriginal TEXT,
    Conjunto VARCHAR(255),
    Anotaciones TEXT,
    NumerosInterseccion TEXT,
    DocumentacionAsociada TEXT,
    FOREIGN KEY (ID_Tecnica) REFERENCES SeccionTecnica(ID_Tecnica) ON DELETE CASCADE
);

-- Desarrollador

-- Insercion de datos ejemplo

-- Inserción en SeccionTecnica
INSERT INTO SeccionTecnica (NumeroInventario, ClaveTecnica, ProcesoFotografico, FondoColeccion, Formato, NumeroNegativoCopia, Tipo)
VALUES ('INV123', 'CT123', 'Proceso 1', 'Colección A', 'Formato A', 'Negativo 123', 'Tipo 1');

-- Obtener el ID_Tecnica del registro insertado
SET @last_id := LAST_INSERT_ID();

-- Inserción en Clave
INSERT INTO Clave (ID_Tecnica)
VALUES (@last_id);

-- Inserciones en las tablas relacionadas
INSERT INTO Datacion (ID_Tecnica, FechaAsunto, FechaToma) VALUES (@last_id, '2023-01-01', '2023-01-02');
INSERT INTO UbicacionGeografica (ID_Tecnica, LugarAsunto, LugarToma) VALUES (@last_id, 'Lugar Asunto', 'Lugar Toma');
INSERT INTO Epocario (ID_Tecnica, Epoca) VALUES (@last_id, 'Epoca 1');
INSERT INTO Autoria (ID_Tecnica, Autor, AutorPrimigenio, AgenciaEstudio, EditorColeccionista, Lema) VALUES (@last_id, 'Autor 1', 'Autor Primigenio 1', 'Agencia 1', 'Editor 1', 'Lema 1');
INSERT INTO Indicativo (ID_Autoria, Sello, Cuño, Firma, Etiqueta, Imprenta, Otro) VALUES (LAST_INSERT_ID(), 'Sello 1', 'Cuño 1', 'Firma 1', 'Etiqueta 1', 'Imprenta 1', 'Otro 1');
INSERT INTO Denominacion (ID_Tecnica, TituloOrigen, TituloCatalografico, TituloSerie) VALUES (@last_id, 'Titulo Origen 1', 'Titulo Catalografico 1', 'Titulo Serie 1');
INSERT INTO Descriptores (ID_Tecnica, TemaPrincipal, Descriptores) VALUES (@last_id, 'Tema Principal 1', 'Descriptor 1, Descriptor 2');
INSERT INTO Protagonistas (ID_Tecnica, Personajes) VALUES (@last_id, 'Personaje 1, Personaje 2');
INSERT INTO Observaciones (ID_Tecnica, InscripcionOriginal, Conjunto, Anotaciones, NumerosInterseccion, DocumentacionAsociada) VALUES (@last_id, 'Inscripcion Original 1', 'Conjunto 1', 'Anotacion 1', 'Numero Interseccion 1', 'Documentacion Asociada 1');

-- Busqueda de los datos
SELECT *
FROM SeccionTecnica ST
LEFT JOIN Clave C ON ST.ID_Tecnica = C.ID_Tecnica
LEFT JOIN Datacion D ON ST.ID_Tecnica = D.ID_Tecnica
LEFT JOIN UbicacionGeografica UG ON ST.ID_Tecnica = UG.ID_Tecnica
LEFT JOIN Epocario E ON ST.ID_Tecnica = E.ID_Tecnica
LEFT JOIN Autoria A ON ST.ID_Tecnica = A.ID_Tecnica
LEFT JOIN Indicativo I ON A.ID_Autoria = I.ID_Autoria
LEFT JOIN Denominacion DN ON ST.ID_Tecnica = DN.ID_Tecnica
LEFT JOIN Descriptores DS ON ST.ID_Tecnica = DS.ID_Tecnica
LEFT JOIN Protagonistas P ON ST.ID_Tecnica = P.ID_Tecnica
LEFT JOIN Observaciones O ON ST.ID_Tecnica = O.ID_Tecnica
WHERE ST.ID_Tecnica = 1; -- Cambia 1 por el ID_Tecnica específico que deseas consultar

ALTER TABLE Usuarios
MODIFY COLUMN TipoUsuario ENUM('General', 'Admin') NOT NULL;

-- Admin
INSERT INTO Usuarios (Usuario, Contraseña, TipoUsuario) VALUES ('admin', '1235', 'Admin');



