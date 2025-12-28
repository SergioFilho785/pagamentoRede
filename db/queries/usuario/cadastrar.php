<?php

require __DIR__ . '/../../conexao.php';
require __DIR__ . '/../../../utils/gerarHash.php';
require __DIR__ . '/../../../utils/jwt.php';
require __DIR__ . '/../../../utils/refreshToken.php';
require __DIR__ . '/../token/salvarRefreshToken.php';

$sql = "INSERT INTO usuarios (nome, email, telefone, senha_hash) VALUES (?, ?, ?, ?)";

try {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "JSON inválido"
        ]);
        exit;
    }

    // no futuro pode ser passado como parâmetro
    $nome = $input["nome"];
    $email = $input["email"];
    $telefone = $input["telefone"];
    $senha = gerarHash($input["senha"]);



    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);

    if ($stmt->execute()) {

        $usuario = [
            "id"   => $conn->insert_id,
            "nome" => $nome
        ];     
           
        $jwt = gerarJWT(
            ["uid" => $usuario["id"]],
            $cred["jwtSecret"],
            (int) $cred["jwtValidade"], // 15 minutos
        );

        $refreshToken = gerarRefreshToken();
        $refreshHash  = hashRefreshToken($refreshToken);   

        $respostaRefreshToken = salvarRefreshToken($conn, $usuario["id"], $refreshHash);


        echo json_encode ([
            "status"   => "ok",
            "mensagem" => "Cadastro realizado com sucesso!",
        "access_token" => $jwt["jwt"],
        "expires_at" => $jwt["expires_at"],
        "refresh_token" => $refreshToken,
        "usuario" => [
            "id"   => $usuario["id"],
            "nome" => $usuario["nome"]
        ]
        ]);
    } else {
        echo json_encode([
            "status" => "erro",
            "mensagem" => $stmt->error
        ]);
    }

} catch (mysqli_sql_exception $e) {

    $msg = $e->getMessage();

    if ($e->getCode() === 1062) {

        if (str_contains($msg, 'usuarios.email')) {
            $mensagem = "Email já cadastrado";
            $campo = "email";
        } 
        elseif (str_contains($msg, 'usuarios.telefone')) {
            $mensagem = "Telefone já cadastrado";
            $campo = "telefone";
        } 
        else {
            $mensagem = "Registro duplicado";
            $campo = "desconhecido";
        }

        http_response_code(409);
        echo json_encode([
            "status" => "erro",
            "campo" => $campo,
            "mensagem" => $mensagem
        ]);
        exit;
    }

    error_log($e->getMessage());

    http_response_code(500);
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro interno do servidor"
    ]);
}

?>
