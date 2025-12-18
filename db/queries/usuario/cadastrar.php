<?php

require __DIR__ . '/../../conexao.php';
require __DIR__ . '/../../../utils/gerarHash.php';

$sql = "INSERT INTO usuarios (nome, email, telefone, senha_hash) VALUES (?, ?, ?, ?)";

try {
    $input = json_decode(file_get_contents("php://input"), true);

    // no futuro pode ser passado como parâmetro
    $nome = $input["nome"];
    $email = $input["email"];
    $telefone = $input["telefone"];
    $senha = gerarHash($input["senha"]);

    if (!$input) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "JSON inválido"
        ]);
        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);

    if ($stmt->execute()) {
        echo json_encode ([
            "status"   => "ok",
            "mensagem" => "Cadastro realizado com sucesso!"
        ]);
    } else {
        echo json_encode([
            "status" => "erro",
            "mensagem" => $stmt->error
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => $e->getMessage()
    ]);
}

?>
