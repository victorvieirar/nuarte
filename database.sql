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
    name varchar(255) not null
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
    block int not null default '0',
    primary key (enrollment),
    foreign key (course) references courses(id)
);

create table reservations
(
	reservationDate date not null,
    reservationEnd date not null,
    backDate date null default null,
    instrument int not null,
    studentEnrollment varchar(30) not null,
    status int not null default '0',
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
(1, "Guitarra Strinberg"),
(2, "Bateria Acústica"),
(3, "Bateria Elétrica"),
(4, "Sanfona Elétrica");

insert into admins values
('nuarte', '123');

DELIMITER $ 
CREATE EVENT checkAll 
    ON SCHEDULE EVERY 1 DAY
    STARTS 'DATA-DE-HOJE-YYYY-MM-DD 23:59' --precisa ser no futuro
    DO BEGIN 
        update students set block = block + 1 WHERE enrollment in (select studentEnrollment from reservations where reservationEnd < CURRENT_DATE()); --adiciona block aos atrasado
        update reservations set status = 2 where studentEnrollment in (select studentEnrollment from reservations where reservationEnd < CURRENT_DATE()); --nega os pedidos futuros dos atrasados
        update students set block = block - 1 where block > 0 and enrollment not in (select studentEnrollment from reservations where reservationEnd < CURRENT_DATE()); --retira um dia de punição dos atrasados que devolveram
    END
$ DELIMITER ;
