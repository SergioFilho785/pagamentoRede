<?php

//Redireciona para o app

function retornar($resposta) {

    // transforma string JSON em array associativo
    if (is_string($resposta)) {
        $resposta = json_decode($resposta, true);
    }

    // garante que não está null
    if (!is_array($resposta)) {
        echo json_encode([
            "status" => "erro",
            "redirect" => "fracasso.html",
            "mensegem" => $resposta
        ]);
        exit;
    }

    // pega o returnCode corretamente
    $returnCode = $resposta["returnCode"] ?? null;

    if ($returnCode === "00") {
        $redirect = "sucesso.html";
    } else {
        $redirect = "fracasso.html";
    }

    echo json_encode([
        //"status" => $resposta,
        "redirect" => $redirect,
        "mensegem" => $resposta
    ]);
    exit;
}
