<?php

//Gera o token

function gerarToken($clientId, $clientSecret, $url) {
    // Monta o Authorization: Basic base64(clientId:clientSecret)
    $credentials = base64_encode("$clientId:$clientSecret");

    // Monta o corpo da requisição
    $data = http_build_query([
        "grant_type" => "client_credentials"
    ]);

    // Inicializa o cURL
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic $credentials",
            "Content-Type: application/x-www-form-urlencoded"
        ]
    ]);

    // Executa a requisição
    $res = curl_exec($curl);

    // Verifica erros de cURL
    if ($err = curl_error($curl)) {
        echo "Erro no cURL: $err";
        curl_close($curl);
        exit;
    }

    curl_close($curl);

    // Decodifica resposta
    $response = json_decode($res, true);

    return $response["access_token"];
}