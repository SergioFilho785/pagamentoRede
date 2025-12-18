<?php

function migration_create_usuarios($conn) {
    $sql = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(120) UNIQUE NOT NULL,
            telefone VARCHAR(20) UNIQUE NOT NULL,
            senha_hash VARCHAR(255) NOT NULL,
            criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ";

    return $conn->query($sql);
}