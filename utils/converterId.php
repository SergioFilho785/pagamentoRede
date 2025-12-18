<?php
function converterId($valor) {
    if (!is_numeric($valor)) {
        throw new Exception("O valor informado não é um inteiro válido.");
    }

    return (int)$valor;
}