<?php

function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function gerarJWT(array $payload, string $secret, int $expSegundos = 900) {
    $header = [
        "alg" => "HS256",
        "typ" => "JWT"
    ];

    $payload["iat"] = time();
    $payload["exp"] = time() + $expSegundos;

    $headerEncoded  = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac(
        "sha256",
        "$headerEncoded.$payloadEncoded",
        $secret,
        true
    );

    $signatureEncoded = base64UrlEncode($signature);

    return "$headerEncoded.$payloadEncoded.$signatureEncoded";
}
