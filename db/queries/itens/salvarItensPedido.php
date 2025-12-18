<?php

function salvarItensPedido($conn, $pedido_id, $produtos) {
    $sql = "INSERT INTO itens_pedido (pedido_id, nome, quantidade, valor_unitario) VALUES ";
    $valores = [];
    $tipos = "";
    $params = [];

    try {
        foreach ($produtos as $i => $p) {

            $valores[] = "(?, ?, ?, ?)";

            $tipos .= "issi";

            $params[] = $pedido_id;
            $params[] = $p["nome"];
            $params[] = (int)$p["qtdd"];
            $params[] = (int)$p["preco"];
        }

        $sql .= implode(", ", $valores);

        //return $sql;

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($tipos, ...$params);

        if ($stmt->execute()) {
            return [
                "status" => "ok",
                "mensagem" => "Itens inseridos com sucesso!"
            ];
        } else {
            return [
                "status" => "erro",
                "mensagem" => $stmt->error
            ];
        }

    } catch(Exception $e) {
        echo json_encode(["erro" => $e->getMessage()]);
    }
}