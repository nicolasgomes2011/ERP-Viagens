<?php
require 'config.php'; // Importa a conexão com o banco

try {
    $sql = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        senha VARCHAR(255) NOT NULL,
        cargo ENUM('admin', 'vendedor', 'financeiro') NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS clientes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        telefone VARCHAR(20),
        endereco TEXT,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS pacotes_viagem (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(150) NOT NULL,
        destino VARCHAR(100) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        data_partida DATE NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cliente_id INT NOT NULL,
        pacote_id INT NOT NULL,
        status ENUM('pendente', 'confirmado', 'cancelado') DEFAULT 'pendente',
        data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (cliente_id) REFERENCES clientes(id),
        FOREIGN KEY (pacote_id) REFERENCES pacotes_viagem(id)
    );

    CREATE TABLE IF NOT EXISTS pagamentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT NOT NULL,
        valor DECIMAL(10,2) NOT NULL,
        metodo ENUM('cartao', 'boleto', 'pix') NOT NULL,
        status ENUM('pendente', 'aprovado', 'recusado') DEFAULT 'pendente',
        data_pagamento TIMESTAMP NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    );

    CREATE TABLE IF NOT EXISTS relatorios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tipo VARCHAR(50) NOT NULL,
        descricao TEXT,
        data_geracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";

    $pdo->exec($sql);
    echo "Banco de dados e tabelas criados com sucesso!";
} catch (PDOException $e) {
    die("Erro ao criar banco de dados: " . $e->getMessage());
}
?>