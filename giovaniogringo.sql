CREATE DATABASE intranet_gringo DEFAULT CHARACTER SET utf8mb4;

USE intranet_gringo;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL
);

-- Inserção inicial dos usuários
INSERT INTO usuarios (usuario, senha, nome, email)
VALUES
('graziele.albuquerque', SHA2('Gringo1975', 256), 'Graziele Albuquerque', 'graziele@gringo.com'),
('gabriel.amaral', SHA2('Gringo1975', 256), 'Gabriel Amaral', 'gabriel@gringo.com');

-- Tabela de postagens
CREATE TABLE postagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    link TEXT,
    texto TEXT NOT NULL,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_formatada VARCHAR(100)
);
