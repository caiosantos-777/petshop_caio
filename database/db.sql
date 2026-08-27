create database caio_petshop;
use caio_petshop;

create table clientes(
    id int auto_increment primary key,
    nome varchar(100) not null,
    email varchar(100) not null,
    telefone varchar(20)
);
create table pets(
    id int auto_increment primary key,
    nome varchar(100) not null,
    especie varchar(50) not null,
    raca varchar(50),
    idade int,
    cliente_id int,
    foreign key (cliente_id) references clientes(id)
);