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

CREATE TABLE IF NOT EXISTS euromillones_numeros_stats (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador',
	`numero` int(3) COMMENT 'Numeros de la combinacion (del 1 al 50)',
	`cantidad` int(6) COMMENT 'Cantidad de vecez que ha salido un numero a lo largo del tiempo',
	PRIMARY KEY (`id`), 
	UNIQUE KEY `numero` (`numero`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de estadisticas de numeros del euromillones';

CREATE TABLE IF NOT EXISTS euromillones_estrellas_stats (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador',
	`estrella` int(3) COMMENT 'Numeros de la combinacion de las estrellas (del 1 al 12)',
	`cantidad_estrella` int(6) COMMENT 'Cantidad de vecez que ha salido un numero a lo largo del tiempo',
	PRIMARY KEY (`id`), 
	UNIQUE KEY `estrella` (`estrella`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de estadisticas de las estrellas del euromillones';

CREATE TABLE IF NOT EXISTS primitiva_numeros_stats (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador',
	`numero` int(3) COMMENT 'Numeros de la combinacion (del 1 al 49)',
	`cantidad` int(6) COMMENT 'Cantidad de vecez que ha salido un numero a lo largo del tiempo',
	PRIMARY KEY (`id`), 
	UNIQUE KEY `numero` (`numero`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de estadisticas de numeros de la primitiva';

CREATE TABLE IF NOT EXISTS primitiva_complementario_stats (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador',
	`numero` int(3) COMMENT 'Numero complementario (del 1 al 49)',
	`cantidad` int(6) COMMENT 'Cantidad de vecez que ha salido un numero a lo largo del tiempo',
	PRIMARY KEY (`id`), 
	UNIQUE KEY `numero` (`numero`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de estadisticas del numero complementario de la primitiva';

CREATE TABLE IF NOT EXISTS primitiva_reintegro_stats (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador',
	`numero` int(3) COMMENT 'Numeros de reintegro (del 0 al 9)',
	`cantidad` int(6) COMMENT 'Cantidad de vecez que ha salido un numero a lo largo del tiempo',
	PRIMARY KEY (`id`), 
	UNIQUE KEY `numero` (`numero`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de estadisticas del numero de reintegro de la primitiva';

CREATE TABLE IF NOT EXISTS primitiva_joker_stats (
	`id` int(5) NOT NULL AUTO_INCREMENT COMMENT 'Identidicador',
	`numero` int(3) COMMENT 'Numeros de la combinacion del joker (del 0 al 9)',
	`cantidad` int(6) COMMENT 'Cantidad de vecez que ha salido un numero a lo largo del tiempo',
	PRIMARY KEY (`id`), 
	UNIQUE KEY `numero` (`numero`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla de estadisticas de los numeros del joker de la primitiva';