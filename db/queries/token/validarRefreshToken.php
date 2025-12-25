<?php

require __DIR__ . '/../../conexao.php';
require __DIR__ . '/../../../utils/jwt.php';
require __DIR__ . '/../../../utils/refreshToken.php';
$cred = require __DIR__ . '/../../../config/credenciais.php';

    $sql = "SELECT id, usuario_id, expires_at FROM refresh_tokens WHERE token_hash = ? LIMIT 1";

try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input || empty($input['refresh_token'])) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Refresh token ausente"
        ]);
        exit;
    }

    $refreshToken = $input['refresh_token'];
    $refreshHash  = hashRefreshToken($refreshToken);
    $now = time();

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $refreshHash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Refresh token inválido ou expirado"
        ]);
        exit;
    }

    $row = $result->fetch_assoc();

    if ($row['expires_at'] < $now) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Refresh token expirado"
        ]);
        exit;
    }

    // GERA NOVO ACCESS TOKEN
    $newJwt = gerarJWT(
        ["uid" => $row["usuario_id"]],
        $cred["jwtSecret"],
        $cred["jwtValidade"]
    );

    echo json_encode([
        "status" => "ok",
        "mensagem" => "novo token gerado",
        "access_token" => $newJwt["jwt"],
        "expires_at" => $newJwt["expires_at"],
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => $e->getMessage()
    ]);
}