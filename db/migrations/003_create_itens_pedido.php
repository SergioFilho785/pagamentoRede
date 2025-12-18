<?php

function migration_create_itens_pedido($conn) {
    $sql = "
        CREATE TABLE IF NOT EXISTS itens_Pedido (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pedido_id INT NOT NULL,
            nome VARCHAR(50) NOT NULL,
            descricao VARCHAR(150),
            quantidade INT NOT NULL,
            valor_unitario INT NOT NULL,
            FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        );
    ";

    return $conn->query($sql);
}
