CREATE TABLE `comentarios` (
	"id"	integer,
	"comentario"	text NOT NULL,
	"fecha"	text,
	"idUserOrigen"	INTEGER NOT NULL,
	"idUserDestino"	INTEGER NOT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
)catalogo