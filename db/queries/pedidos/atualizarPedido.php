<?php

function atualizarPedido($conn, $resposta, $pedido_id)
{
    $sql = "UPDATE pedidos SET status = ?, tid = ? WHERE id = ?";

    try {

        if (is_string($resposta)) {
            $resposta = json_decode($resposta, true);
        }

        if (!is_array($resposta)) {
            throw new Exception("Resposta inválida ao atualizar pedido");
        }


        $status = $resposta["returnCode"] == "00" ? "aprovado" : "recusado";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $status, $resposta["tid"], $pedido_id);
        $stmt->execute();

        return [
            "status" => "ok",
            "mensagem" => "Pedido atualizado!"
        ];

    } catch (Exception $e) {
        return [
            "status" => "erro",
            "mensagem" => $e->getMessage()
        ];
    }
}