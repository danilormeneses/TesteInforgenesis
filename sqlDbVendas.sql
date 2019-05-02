use vendas;

create table usuarios (
	id int not null auto_increment primary key,
    nome varchar(255),
    matricula varchar(255),
    senha varchar(255),
    status tinyint,
    created datetime
)engine=InnoDB charset utf8;

create table clientes(
	id int not null auto_increment primary key,
    usuario_id int,
    nome varchar(255),
    cpf varchar(255),
    rg varchar(255),
    endereco varchar(255),
    numero integer,
    estado varchar(255),
    cidade varchar(255),
    renda float,
    status tinyint,
    created datetime,
    foreign key (usuario_id) references usuarios(id) on delete cascade    
)engine=innoDB charset utf8;

create table produtos(
	id int not null auto_increment primary key,
    usuario_id int,
    descricao varchar(45),
    detalhamento text,
    preco_vista float,
    preco_prazo float,
    codigo_barras varchar(255),
    status tinyint,
    created datetime,
    foreign key (usuario_id) references usuarios(id) on delete cascade
)engine=innoDB charset utf8;


create table vendas (
	id int not null auto_increment primary key,
    produto_id int,
    usuario_id int,
    cliente_id int,
    quantidade int,
    valor_total float,
    forma_pagamento varchar(255),
    status tinyint,
    created datetime,
    updated datetime,
    foreign key(produto_id) references produtos(id) on delete cascade,
    foreign key (cliente_id) references clientes(id) on delete cascade,
    foreign key (usuario_id) references usuarios(id) on delete cascade
)engine=innoDB charset utf8;






