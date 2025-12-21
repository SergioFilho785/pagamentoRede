<?php

function salvarRefreshToken($conn, $idUsuario, $tokenHash) {
    $sql = "INSERT INTO refresh_tokens (usuario_id, token_hash, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))";

    try {

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $idUsuario, $tokenHash);

        if($stmt->execute()) {
            return [
                "status"     => "ok",
                "mensagem"   => "Refresh token salvo!",
            ];
        }

        return [
            "status"   => "erro",
            "mensagem" => "Falha ao salvar refresh token" . $stmt->error
        ];

    } catch(Exception $e) {
        echo json_encode(["erro" => $e->getMessage()]);
    }
}