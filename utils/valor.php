<?php

//Percorre todo o array de produtos e retorna a soma de todos eles em centavos

function converterValor($produtos) {
    $valor = 0;
    foreach ($produtos as $p) {
        $valor += ($p["preco"] * $p["qtdd"]) * 100; 
    }
    return $valor;
}