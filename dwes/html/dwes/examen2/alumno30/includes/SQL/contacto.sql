/* Borrar la base de datos si existe */
DROP DATABASE IF EXISTS examen30;
/* CREA LA BASE DE DATOS */
CREATE DATABASE examen30 character set utf8 collate utf8_general_ci;
/* USA LA BASE DE DATOS */
use examen30;
/* Creamos una categoría para el usuario, esto es una futura implementación */
/* FUTURA IMPLEMENTACIÓN agenda propia */
CREATE TABLE rol (
	id_rol		int(11) not null auto_increment comment 'ID del rol del usuario',
	nombre 		varchar(100) not null comment 'Nombre del rol del usuario',
	unique(nombre),
	PRIMARY KEY(id_rol)
);
INSERT INTO rol(nombre) VALUES(UPPER('admin'));
INSERT INTO rol(nombre) VALUES(UPPER('user'));


/* Crear la tabla */
CREATE TABLE usuario(
	id_usu		int(11) not null auto_increment comment 'PK de la Base de datos',
	nombre		varchar(50) not null comment 'Nombre del usuario de la base de datos',
	apellidos	varchar(100) not null comment 'Apellidos del usuario de la BD',
	email		varchar(100) not null comment 'El email del usuario',
	pass		varchar(50) not null comment 'Contraseña del usuario (cifrada con MD5)',
	unique(email),
	PRIMARY KEY(id_usu)
);
/* INSERCIÓN DE DATOS */
insert into  usuario(nombre, apellidos, email, pass) values (UPPER('Emmanuel'),UPPER('Valverde Ramos'), LOWER('evrtrabajo@gmail.com'), MD5('Password1'));
insert into  usuario(nombre, apellidos, email, pass) values (UPPER('Emmanuel'),UPPER('Valverde Ramos'), LOWER('usuario1@gmail.com'), MD5('Password1'));

/* Crear la tabla id categoria*/
CREATE TABLE categoria(
	id_cat		int(5) not null auto_increment COMMENT 'PK de categoria',
	nombre_cat	varchar(100) not null COMMENT 'Nombre de la categoria',
	PRIMARY KEY (id_cat)
);

/* INSERCIÓN DE DATOS */
insert into  categoria(nombre_cat) values (UPPER('familia'));
insert into  categoria(nombre_cat) values (UPPER('amigos'));
insert into  categoria(nombre_cat) values (UPPER('conocidos'));

/* Crear la tabla contacto */
CREATE TABLE contacto(
	id_con		int(11) not null auto_increment comment 'PK de contacto',
	nombre		varchar(50) not null comment 'Nombre del contacto',
	apellidos	varchar(100) not null comment 'Apellidos del contacto',
	telf		varchar(13) not null comment 'telefono del contacto',
	email		varchar(100) not null comment 'email del contacto',
	direccion 	varchar(255) not null comment 'direccion del contacto',
	fech_al		date not null comment 'fecha de alta del usuario',
	id_cat		int(11) not null comment 'FK de la categoría del usuario',
	id_usu 		int(11) not null comment 'El id del usuario al que el contacto está asociado',
	img			varchar(255) comment 'imagen del contacto',
	unique(email, id_usu),
	FOREIGN KEY(id_cat) references  categoria(id_cat) on update cascade,
	FOREIGN KEY(id_usu) references  usuario(id_usu) on update cascade,
	PRIMARY KEY(id_con)
);
/* INSERCIÓN DE DATOS */
insert into  contacto(nombre,apellidos,telf, email, direccion, fech_al ,id_cat, id_usu) values(UPPER('Luis'), UPPER('Cavero'), '666666666', LOWER('luiscavero92@gmail.com'), 'calle no la se', '1992-06-09' ,2, 1);
insert into  contacto(nombre,apellidos,telf, email, direccion, fech_al ,id_cat, id_usu) values(UPPER('Luis'), UPPER('Cavero'), '666666666', LOWER('luiscavero92@gmail.com'), 'calle no la se', '1992-06-09' ,2, 2);
insert into  contacto(nombre,apellidos,telf, email, direccion, fech_al ,id_cat, id_usu) values(UPPER('España'), UPPER('Peña fiel'), '666666666', LOWER('luiscavelkjsldro92@gmail.com'), 'calle no la se', '1992-06-09' ,2, 1);

CREATE TABLE grupo(
	id 			int(11) not null auto_increment comment 'Clave primaria de la tabla grupos',
	nombre 		varchar(50) not null comment 'Nombre del grupo',
	descripcion	varchar(255) not null comment 'Descripción del grupo',
	id_usu		int(11) not null comment 'FK del usuario',
	unique(nombre, id_usu),
	FOREIGN KEY (id_usu) references usuario(id_usu) on update cascade,
	PRIMARY KEY(id)
);
/* Inserciones */
INSERT INTO grupo (nombre, descripcion, id_usu) VALUES(UPPER('CONTACTOS'), 'contactos de inicio', 1);
INSERT INTO grupo (nombre, descripcion, id_usu) VALUES(UPPER('Amigos de toda la vida'), 'Grupo de amigos', 1);
INSERT INTO grupo (nombre, descripcion, id_usu) VALUES(UPPER('DAW'), 'Grupo de clase', 2);

/* creamos la N:M que permitirá añadir contactos a un grupo */

CREATE TABLE grupocontactos(
	id 			int(11) not null auto_increment comment 'PK de la tabla N:M de grupo contactos',
	id_con		int(11) not null comment 'FK del contacto',
	id_grupo	int(11) not null comment 'FK del grupo',
	fecha_alta 	date not null,
	unique (id_con, id_grupo),
	PRIMARY KEY (id),
	FOREIGN KEY (id_con) references contacto(id_con) on update cascade,
	FOREIGN KEY (id_grupo) references grupo(id) on update cascade
);

/* inserciones de contactos en grupos */
/* GRUPO 1 */
INSERT INTO grupocontactos (id_con, id_grupo, fecha_alta) VALUES (1,1,'1992-06-09');
INSERT INTO grupocontactos (id_con, id_grupo, fecha_alta) VALUES (3,1,'1992-06-09');

/* GRUPO 2 */
INSERT INTO grupocontactos (id_con, id_grupo, fecha_alta) VALUES (1,2,'1992-06-09');
