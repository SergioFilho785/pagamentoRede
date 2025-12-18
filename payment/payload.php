<?php

//Monta o payload que será enviado para a Rede

function gerarPayload($dadosPagamento, $valor, $reference) {
    $payload = [
        "capture" => true,
        "kind" => $dadosPagamento["cartao"],
        "reference" => $reference, //TRATAR FUTURAMENTE DATA/HORA/ID_CLIENTE
        "amount" => $valor,
        "installments" => $dadosPagamento["parcelas"],
        "cardholderName" => $dadosPagamento["nome"],
        "cardNumber" => $dadosPagamento["numeroCartao"],
        "expirationMonth" => $dadosPagamento["mesVencimento"],
        "expirationYear" => $dadosPagamento["anoVencimento"],
        "securityCode" => $dadosPagamento["cvv"],
    ];

    return $payload;
}