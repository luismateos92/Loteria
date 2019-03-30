DROP DATABASE IF EXISTS loterias;

CREATE DATABASE IF NOT EXISTS loterias;

USE loterias;

CREATE TABLE IF NOT EXISTS euromillones (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador', 
	`fecha` varchar(20) NOT NULL COMMENT 'Fecha del sorteo', 
	`combinacion` varchar(50) NOT NULL COMMENT 'Combinacion ganadora', 
	`estrellas` int(6) NOT NULL COMMENT 'Estrellas ganadoras', 
	`primer_numero` int(3) NOT NULL COMMENT 'Primer numero de la combinacion ganadora', 
	`segundo_numero` int(3) NOT NULL COMMENT 'Segundo numero de la combinacion ganadora', 
	`tercer_numero` int(3) NOT NULL COMMENT 'Tercer numero de la combinacion ganadora', 
	`cuarto_numero` int(3) NOT NULL COMMENT 'Cuarto numero de la combinacion ganadora', 
	`quinto_numero` int(3) NOT NULL COMMENT 'Quinto numero de la combinacion ganadora', 
	`primera_estrella` int(3) NOT NULL COMMENT 'Primer numero de la estrella ganadora', 
	`segunda_estrella` int(3) NOT NULL COMMENT 'Segundo numero de la estrella ganadora', 
	PRIMARY KEY (`id`), 
	UNIQUE KEY `fecha` (`fecha`) 
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de euromillones';

CREATE TABLE IF NOT EXISTS primitiva (
 	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador', 
 	`fecha` varchar(20) NOT NULL COMMENT 'Fecha del sorteo', 
 	`combinacion` varchar(50) NOT NULL COMMENT 'Combinacion ganadora', 
 	`complementario` int(3) NOT NULL COMMENT 'Numero complementario', 
 	`reintegro` int(3) NOT NULL COMMENT 'Numero complementario', 
 	`primer_numero` int(3) NOT NULL COMMENT 'Primer numero de la combinacion ganadora', 
 	`segundo_numero` int(3) NOT NULL COMMENT 'Segundo numero de la combinacion ganadora', 
 	`tercer_numero` int(3) NOT NULL COMMENT 'Tercer numero de la combinacion ganadora', 
 	`cuarto_numero` int(3) NOT NULL COMMENT 'Cuarto numero de la combinacion ganadora', 
 	`quinto_numero` int(3) NOT NULL COMMENT 'Quinto numero de la combinacion ganadora', 
 	`sexto_numero` int(3) NOT NULL COMMENT 'Sexto numero de la combinacion ganadora',
 	`joker` int(8) NOT NULL COMMENT 'Numero Joker ganador', 
 	`primer_numero_joker` int(3) NOT NULL COMMENT 'Primer numero del joker', 
 	`segundo_numero_joker` int(3) NOT NULL COMMENT 'Segundo numero del joker', 
 	`tercer_numero_joker` int(3) NOT NULL COMMENT 'Tercer numero del joker', 
 	`cuarto_numero_joker` int(3) NOT NULL COMMENT 'Cuarto numero del joker', 
 	`quinto_numero_joker` int(3) NOT NULL COMMENT 'Quinto numero del joker', 
 	`sexto_numero_joker` int(3) NOT NULL COMMENT 'Sexto numero del joker', 
 	`septimo_numero_joker` int(3) NOT NULL COMMENT 'Septimo numero del joker', 
 	PRIMARY KEY (`id`), 
 	UNIQUE KEY `fecha` (`fecha`)
 	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de la primitiva';
