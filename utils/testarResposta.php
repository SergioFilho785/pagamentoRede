<?php

function testarResposta($resposta) {

        echo json_encode([
        //"redirect" => $redirect,
        "mensegem" => $resposta
    ]);
}