CREATE DATABASE APPSALON;
USE APPSALON;

CREATE TABLE USUARIOS (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL,
	apellido VARCHAR(60) NOT NULL,
	email VARCHAR(30) NOT NULL UNIQUE,
	telefono VARCHAR(10) NOT NULL,
	admin TINYINT,
	confirmado TINYINT,
	token VARCHAR(15),
	PRIMARY KEY (id)
); 

CREATE TABLE SERVICIOS ( 
	id INT AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL,
	precio DECIMAL(5,2) NOT NULL,
	PRIMARY KEY (id)
);

INSERT INTO servicios ( nombre, precio ) VALUES
	('Corte de Cabello Niño', 60),
	('Corte de Cabello Hombre', 80),
	('Corte de Barba', 60),
	('Peinado Mujer', 80),
	('Peinado Hombre', 60),
	('Tinte',300),
	('Uñas', 400),
	('Lavado de Cabello', 50),
	('Tratamiento Capilar', 150);

CREATE TABLE CITAS (
	id INT AUTO_INCREMENT,
	fecha DATE NOT NULL,
	hora TIME NOT NULL,
	usuario_id INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (usuario_id) REFERENCES USUARIOS (id) ON DELETE CASCADE
);

CREATE TABLE CITAS_SERVICIOS (
	id INT AUTO_INCREMENT,
    cita_id INT NOT NULL,
    servicio_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (cita_id) REFERENCES CITAS (id) ON DELETE CASCADE,
    FOREIGN KEY (servicio_id) REFERENCES SERVICIOS (id) ON DELETE CASCADE
);