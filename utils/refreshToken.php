<?php

function gerarRefreshToken()
{
    return bin2hex(random_bytes(64)); // 128 caracteres
}

function hashRefreshToken(string $token)
{
    return hash("sha256", $token);
}