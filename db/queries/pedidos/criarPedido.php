<?php

function criarPedido($conn, $usuario_id, $reference, $pagamento, $valor_total)
{
    $sql = "INSERT INTO pedidos (usuario_id, reference, pagamento, valor_total) VALUES (?, ?, ?, ?)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $usuario_id, $reference, $pagamento, $valor_total);

        if ($stmt->execute()) {
            $pedido_id = $stmt->insert_id;

            return [
                "status"     => "ok",
                "mensagem"   => "Pedido registrado com sucesso!",
                "pedido_id"  => $pedido_id
            ];
        }

        return [
            "status"   => "erro",
            "mensagem" => "Falha ao registrar pedido!"
        ];

    } catch (Exception $e) {
        return [
            "status"   => "erro",
            "mensagem" => $e->getMessage()
        ];
    }
}
