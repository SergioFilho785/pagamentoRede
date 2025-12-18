<?php

$cred = require __DIR__ . '/../config/credenciais.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($cred["urlDatabase"], $cred["userDatabase"], $cred["senhaDatabase"], $cred["database"]);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die(json_encode([
        "status" => "erro",
        "mensagem" => "Falha na conexão com o banco de dados",
        "detalhe" => $e->getMessage()
    ]));    
}