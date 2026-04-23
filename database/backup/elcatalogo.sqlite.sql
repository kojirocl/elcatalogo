BEGIN TRANSACTION;
DROP TABLE IF EXISTS "comentarios";
CREATE TABLE "comentarios" (
	"id"	integer,
	"comentario"	text NOT NULL,
	"fecha"	text,
	"idUserOrigen"	INTEGER NOT NULL,
	"idUserDestino"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "contrato";
CREATE TABLE "contrato" (
	`id`	INTEGER NOT NULL UNIQUE,
	`idUser`	INTEGER NOT NULL,
	`idSuscripcion`	INTEGER NOT NULL,
	`fecha_registro`	NUMERIC,
	`vigencia`	INTEGER,
	`info_cupon`	TEXT,
	`info_descuento`	TEXT,
	`info_suscripcion`	TEXT,
	`valor_pagado`	NUMERIC,
	`vigente`	INTEGER NOT NULL DEFAULT 1,
	PRIMARY KEY(`id` AUTOINCREMENT)
);
DROP TABLE IF EXISTS "cupones";
CREATE TABLE "cupones" (
	"id"	INTEGER UNIQUE,
	"idDescuento"	INTEGER NOT NULL,
	"idUser"	INTEGER NOT NULL,
	"valido"	INTEGER DEFAULT 1,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "descuentos";
CREATE TABLE "descuentos" (
	"id"	INTEGER NOT NULL UNIQUE,
	"codigo"	TEXT NOT NULL,
	"descripcion"	TEXT NOT NULL,
	"descuento"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "grupos";
CREATE TABLE "grupos" (
	"id"	INTEGER UNIQUE,
	"grupo"	TEXT,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "me_gusta";
CREATE TABLE "me_gusta" (
	`id`	INTEGER UNIQUE,
	`idUser_origen`	INTEGER,
	`idMedia`	INTEGER DEFAULT 0,
	`fecha`	NUMERIC,
	PRIMARY KEY(`id` AUTOINCREMENT)
);
DROP TABLE IF EXISTS "media";
CREATE TABLE "media" (
	`id`	integer NOT NULL DEFAULT 1 UNIQUE,
	`idUser`	integer NOT NULL,
	`ubicacion`	text NOT NULL,
	PRIMARY KEY(`id` AUTOINCREMENT)
);
DROP TABLE IF EXISTS "perfil";
CREATE TABLE "perfil" (
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
DROP TABLE IF EXISTS "sessions";
CREATE TABLE `sessions` (
	`session_id` VARCHAR(255),
	`data` TEXT,
	`ip` VARCHAR(45),
	`agent` VARCHAR(300),
	`stamp` INTEGER,
	PRIMARY KEY (`session_id`)
);
DROP TABLE IF EXISTS "sqlite_stat4";
CREATE TABLE sqlite_stat4(tbl,idx,neq,nlt,ndlt,sample);
DROP TABLE IF EXISTS "suscripcion";
CREATE TABLE "suscripcion" (
	"id"	INTEGER NOT NULL UNIQUE,
	"descripcion"	TEXT,
	"dias"	INTEGER,
	"valor"	INTEGER,
	PRIMARY KEY("id" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "tag_perfil";
CREATE TABLE "tag_perfil" (
	"idTag"	INTEGER NOT NULL,
	"idUser"	INTEGER NOT NULL
);
DROP TABLE IF EXISTS "tags";
CREATE TABLE "tags" (
	"idTag"	INTEGER NOT NULL UNIQUE,
	"tag"	TEXT NOT NULL UNIQUE,
	PRIMARY KEY("idTag" AUTOINCREMENT)
);
DROP TABLE IF EXISTS "trafico";
CREATE TABLE "trafico" (
	`id`	INTEGER NOT NULL DEFAULT 1 UNIQUE,
	`idUsuario`	INTEGER NOT NULL,
	`fecha`	NUMERIC,
	`visita`	INTEGER,
	`contacto`	INTEGER,
	PRIMARY KEY(`id` AUTOINCREMENT)
);
DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
	"idUser"	INTEGER UNIQUE,
	"email"	TEXT UNIQUE,
	"password"	TEXT,
	"fecha_ingreso"	NUMERIC DEFAULT CURRENT_TIMESTAMP,
	"codigo_verificacion"	NUMERIC,
	"verificado"	INTEGER DEFAULT 0,
	"fecha_exp_token"	NUMERIC,
	PRIMARY KEY("idUser" AUTOINCREMENT)
);
COMMIT;
