create schema if not exists `ayuda-covid` collate utf8_general_ci;

create table if not exists solicitudes
(
	id int auto_increment
		primary key,
	cedula varchar(15) not null,
	edad int not null,
	barrio int not null,
	estado int default 0 not null
);