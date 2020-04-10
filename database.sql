create schema if not exists `ayuda-covid` collate utf8_general_ci;

use ayuda-covid;

create table if not exists solicitudes
(
	id int auto_increment primary key,
	cedula varchar(15) not null,
	edad varchar(2) not null,
	barrio varchar(15) not null,
	estado int default 0 not null
);
