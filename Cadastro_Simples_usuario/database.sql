-- Script para criar o banco de dados e tabela
-- Execute este script no seu MySQL antes de usar o CRUD

-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS crud_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar o banco de dados
USE crud_db;

-- Criar a tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir alguns dados de exemplo (opcional)
INSERT INTO usuarios (nome, email, telefone) VALUES 
('João Silva', 'joao@email.com', '(11) 99999-9999'),
('Maria Santos', 'maria@email.com', '(11) 88888-8888'),
('Pedro Oliveira', 'pedro@email.com', '(11) 77777-7777');

-- Verificar se os dados foram inseridos
SELECT * FROM usuarios;