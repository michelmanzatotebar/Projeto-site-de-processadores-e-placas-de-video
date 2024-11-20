CREATE DATABASE IF NOT EXISTS loja_hardware;
USE loja_hardware;

CREATE TABLE Produto (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(50) NOT NULL,
    Categoria VARCHAR(50),
    Marca VARCHAR(50),
    Especificacoes TEXT,
    Preco DECIMAL(10,2)
);

CREATE TABLE Cliente (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(50) NOT NULL,
    Endereco TEXT,
    Numero VARCHAR(25),
    Email VARCHAR(25) UNIQUE NOT NULL
);

CREATE TABLE Carrinho (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Email_cliente VARCHAR(50),
    Id_produto INT,
    Quantidade DECIMAL(10,2),
    Preco_produto DECIMAL(10,2),
    Preco_total DECIMAL(10,2)
);

CREATE TABLE Pedido (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Email_cliente VARCHAR(50),
    Quantidade DECIMAL(10,2),
    Preco_total DECIMAL(10,2)
);