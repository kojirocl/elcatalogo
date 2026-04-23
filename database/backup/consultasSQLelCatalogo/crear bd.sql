BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `comentarios` (
	`id`	INTEGER NOT NULL AUTO_INCREMENT,
	`comentario`	text NOT NULL,
	`fecha`	text,
	`idUserOrigen`	INTEGER NOT NULL,
	`idUserDestino`	INTEGER NOT NULL,
	PRIMARY KEY(`id`)
);
CREATE TABLE IF NOT EXISTS `contrato` (
	`id`	INTEGER NOT NULL UNIQUE AUTO_INCREMENT,
	`idUser`	INTEGER NOT NULL,
	`idSuscripcion`	INTEGER NOT NULL,
	`fecha_registro`	NUMERIC,
	`vigencia`	INTEGER,
	`info_cupon`	TEXT,
	`info_descuento`	TEXT,
	`info_suscripcion`	TEXT,
	`valor_pagado`	NUMERIC,
	`vigente`	INTEGER NOT NULL DEFAULT 1,
	PRIMARY KEY(`id`)
);
CREATE TABLE IF NOT EXISTS `cupones` (
	`id`	INTEGER UNIQUE AUTO_INCREMENT,
	`idDescuento`	INTEGER NOT NULL,
	`idUser`	INTEGER NOT NULL,
	`valido`	INTEGER DEFAULT 1,
	PRIMARY KEY(`id`)
);
CREATE TABLE IF NOT EXISTS `descuentos` (
	`id`	INTEGER NOT NULL UNIQUE AUTO_INCREMENT,
	`codigo`	TEXT NOT NULL,
	`descripcion`	TEXT NOT NULL,
	`descuento`	INTEGER NOT NULL,
	PRIMARY KEY(`id`)
);
CREATE TABLE IF NOT EXISTS `grupos` (
	`id`	INTEGER UNIQUE AUTO_INCREMENT,
	`grupo`	TEXT,
	PRIMARY KEY(`id`)
);
CREATE TABLE IF NOT EXISTS `me_gusta` (
	`id`	INTEGER UNIQUE AUTO_INCREMENT,
	`idUser_origen`	INTEGER,
	`idMedia`	INTEGER DEFAULT 0,
	`fecha`	NUMERIC,
	PRIMARY KEY(`id` )
);
CREATE TABLE IF NOT EXISTS `media` (
	`id`	integer NOT NULL DEFAULT 1 UNIQUE AUTO_INCREMENT,
	`idUser`	integer NOT NULL,
	`ubicacion`	text NOT NULL,
	PRIMARY KEY(`id` )
);
CREATE TABLE IF NOT EXISTS `perfil` (
	`idUser`	integer NOT NULL UNIQUE,
	`realname`	text,
	`nickname`	text NOT NULL UNIQUE,
	`descripcion`	text,
	`wsp`	text,
	`region`	TEXT,
	`ciudad`	TEXT,
	`activo`	INTEGER DEFAULT 0,
	`idFotoPerfil`	integer DEFAULT 0,
	`idGrupo`	INTEGER DEFAULT 1,
	UNIQUE(`idUser`),
	PRIMARY KEY(`idUser`),
	FOREIGN KEY(`idUser`) REFERENCES `users`(`idUser`)
);
CREATE TABLE IF NOT EXISTS `suscripcion` (
	`id`	INTEGER NOT NULL UNIQUE AUTO_INCREMENT,
	`descripcion`	TEXT,
	`dias`	INTEGER,
	`valor`	INTEGER,
	PRIMARY KEY(`id` )
);
CREATE TABLE IF NOT EXISTS `tag_perfil` (
	`idTag`	INTEGER NOT NULL,
	`idUser`	INTEGER NOT NULL
);
CREATE TABLE IF NOT EXISTS `tags` (
	`idTag`	INTEGER NOT NULL UNIQUE AUTO_INCREMENT,
	`tag`	TEXT NOT NULL UNIQUE,
	PRIMARY KEY(`idTag` )
);
CREATE TABLE IF NOT EXISTS `trafico` (
	`id`	INTEGER NOT NULL DEFAULT 1 UNIQUE AUTO_INCREMENT,
	`idUsuario`	INTEGER NOT NULL,
	`fecha`	NUMERIC,
	`visita`	INTEGER,
	`contacto`	INTEGER,
	PRIMARY KEY(`id`)
);
CREATE TABLE IF NOT EXISTS `users` (
	`idUser`	INTEGER UNIQUE AUTO_INCREMENT,
	`email`	TEXT UNIQUE,
	`password`	TEXT,
	`fecha_ingreso`	NUMERIC DEFAULT CURRENT_TIMESTAMP,
	`codigo_verificacion`	NUMERIC,
	`verificado`	INTEGER DEFAULT 0,
	PRIMARY KEY(`idUser`)
);
COMMIT;