<?php

require __DIR__ . '/../../conexao.php';
require __DIR__ . '/../../../utils/gerarHash.php';
require __DIR__ . '/../../../utils/jwt.php';
require __DIR__ . '/../../../utils/refreshToken.php';
require __DIR__ . '/../token/salvarRefreshToken.php';

$sql = "SELECT id, nome, senha_hash FROM usuarios WHERE email = ?";
$cred = require __DIR__ . '/../../../config/credenciais.php';

try {

    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "JSON inválido"
        ]);
        exit;
    }

    // no futuro pode ser passado como parâmetro
    $email = $input["email"];
    $senha = $input["senha"];
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Usuário não encontrado"
        ]);
        exit;
    }

    $usuario = $result->fetch_assoc();

    if (!password_verify($senha, $usuario["senha_hash"])) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Senha inválida"
        ]);
        exit;
    }

    //GERA O JWT E O REFRESH TOKEN
    $jwt = gerarJWT(
        ["uid" => $usuario["id"]],
        $cred["jwtSecret"],
        (int) $cred["jwtValidade"], // 15 minutos
    );

    $refreshToken = gerarRefreshToken();
    $refreshHash  = hashRefreshToken($refreshToken);   

    $respostaRefreshToken = salvarRefreshToken($conn, $usuario["id"], $refreshHash);

    //RESPOSTA
    echo json_encode([
        "status" => "ok",
        "mensagem" => "Login realizado com sucesso!",
        "access_token" => $jwt["jwt"],
        "expires_at" => $jwt["expires_at"],
        "refresh_token" => $refreshToken,
        "usuario" => [
            "id"   => $usuario["id"],
            "nome" => $usuario["nome"]
        ]
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => $e->getMessage()
    ]);
}