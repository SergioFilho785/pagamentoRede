<?php

function migration_create_pedidos($conn) {
    $sql = "
        CREATE TABLE IF NOT EXISTS pedidos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario_id INT NOT NULL,
            reference VARCHAR(50) UNIQUE NOT NULL,
            pagamento VARCHAR(10) NOT NULL,
            valor_total INT NOT NULL,
            status VARCHAR(20) DEFAULT 'pendente',
            tid VARCHAR(20),
            retorno_bruto JSON,
            criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        );
    ";

    return $conn->query($sql);
}
