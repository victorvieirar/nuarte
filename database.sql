/**
* Database settings:
*
* [yes] username = root
* [no]  password =                
* [yes] host = localhost
*
*/

create database nuarte;
use nuarte;

create table instruments
(
	reference int not null primary key,
    name varchar(255) not null,
    quantity int not null
);

create table courses
(
	id int not null auto_increment primary key,
    name varchar(255) not null
);

create table students
(
    enrollment varchar(30) not null,
    name varchar(255) not null,
    email varchar(255) not null,
    course int not null,
    password varchar(255) not null,
    primary key (enrollment),
    foreign key (course) references courses(id)
);

create table reservations
(
	reservationDate date not null,
    reservationEnd date not null,
    instrument int not null,
    studentEnrollment varchar(30) not null,
    primary key (reservationDate, instrument, studentEnrollment),
    foreign key (instrument) references instruments(reference),
    foreign key (studentEnrollment) references students(enrollment)
);

create table admins
(
	user varchar(255) not null primary key,
    password varchar(255) not null
);

insert into courses values 
(default, "Informática"), 
(default, "Química"), 
(default, "Administração"), 
(default, "Processos Químicos"),
(default, "Tecnólogo de Análise e Desenvolvimento de Sistemas");

insert into instruments values
(1, "Guitarra Strinberg", 2),
(2, "Bateria Acústica", 1),
(3, "Bateria Elétrica", 1),
(4, "Sanfona Elétrica", 2);