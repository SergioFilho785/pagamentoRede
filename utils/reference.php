<?php
function gerarReference() {
    // AAA20241208153245XYZ
    $data = date("YmdHis"); // 14 chars
    $rand = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)); // 4 chars
    return "REF" . $data . $rand; // total ~ 21 chars
}