<?php

require __DIR__ . "/conexao.php";

// Carrega automaticamente todos os arquivos da pasta migrations
$migrationsPath = __DIR__ . "/migrations";

foreach (glob($migrationsPath . "/*.php") as $arquivo) {
    require_once $arquivo;
}

echo "Executando migrations...\n";

migration_create_usuarios($conn);
migration_create_pedidos($conn);
migration_create_itens_pedido($conn);

echo "Finalizado.\n";