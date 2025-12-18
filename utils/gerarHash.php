<?php
function gerarHash($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}