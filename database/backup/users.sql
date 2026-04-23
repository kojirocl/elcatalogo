BEGIN TRANSACTION;
DROP TABLE IF EXISTS "users";
CREATE TABLE "users" (
	"idUser"	INTEGER UNIQUE,
	"email"	TEXT NOT NULL UNIQUE,
	"password"	TEXT NOT NULL,
	"fecha_ingreso"	TEXT DEFAULT CURRENT_TIMESTAMP,
	"codigo_verificacion"	NUMERIC,
	"verificado"	INTEGER DEFAULT 0,
	"fecha_exp_token"	INTEGER,
	PRIMARY KEY("idUser" AUTOINCREMENT)
);
INSERT INTO "users" VALUES (1,'mail1@gmail.com','$2y$10$qsz4LxljAWiUJu.3/UNHYOZCVJbVwrcwkKDpTmLlHLWgSq7QPWD/m',NULL,1,1,NULL);
INSERT INTO "users" VALUES (2,'mail2@gmail.com','$2y$10$qsz4LxljAWiUJu.3/UNHYOZCVJbVwrcwkKDpTmLlHLWgSq7QPWD/m',NULL,1,1,NULL);
INSERT INTO "users" VALUES (3,'mail3@gmail.com','$2y$10$qsz4LxljAWiUJu.3/UNHYOZCVJbVwrcwkKDpTmLlHLWgSq7QPWD/m',NULL,1,1,NULL);
INSERT INTO "users" VALUES (4,'mail4@gmail.com','$2y$10$qsz4LxljAWiUJu.3/UNHYOZCVJbVwrcwkKDpTmLlHLWgSq7QPWD/m',NULL,1,1,NULL);
INSERT INTO "users" VALUES (20,'mail5@gmail.com','$2y$10$qsz4LxljAWiUJu.3/UNHYOZCVJbVwrcwkKDpTmLlHLWgSq7QPWD/m','1737932432',1,1,NULL);
INSERT INTO "users" VALUES (21,'mail10@gmail.com','$2y$10$WK7orWh1f.RuNgVuUKVpPegPxE9fqrEeefGziDS7G5djZLatF2Cyi','1746103652',1,1,NULL);
INSERT INTO "users" VALUES (40,'mail200@gmail.com','$2y$10$a3brGbxoDpp06NIbg4NSXu0jX/FEDnV5ufkRc5I8zgFBCbCu/ANq2','1747338660',1,1,NULL);
INSERT INTO "users" VALUES (43,'mail201@gmail.com','$2y$10$w5kfmbFqJJ1uYHwLU6XH1eJytvckdI0UR4NBLGAeMi.QYyuKnF10a','1748045208',1,1,NULL);
INSERT INTO "users" VALUES (44,'mail555@gmail.com','$2y$10$R6QoTG5RktcRuTnGF2Yquu4XOsCTW4U.w06OnLY1cktIH7KoIFDiy','1759012585',1,1,NULL);
COMMIT;
