<?php

//Faz a requisiçao para a Rede

function chamarApi($token, $payload, $url){
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $resposta = curl_exec($curl);
    curl_close($curl);

    return $resposta;
}