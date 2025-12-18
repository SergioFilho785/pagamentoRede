<?php

require __DIR__ . '/config/credenciais.php';
require __DIR__ . '/utils/valor.php';
require __DIR__ . '/utils/converterId.php';
require __DIR__ . '/utils/retorno.php';
require __DIR__ . '/utils/reference.php';
require __DIR__ . '/utils/testarResposta.php';
require __DIR__ . '/payment/oauth.php';
require __DIR__ . '/payment/payload.php';
require __DIR__ . '/payment/chamada.php';
require __DIR__ . '/db/conexao.php';
require __DIR__ . '/db/queries/pedidos/criarPedido.php';
require __DIR__ . '/db/queries/pedidos/atualizarPedido.php';
require __DIR__ . '/db/queries/itens/salvarItensPedido.php';

$cred = require __DIR__ . '/config/credenciais.php';
$token = gerarToken($cred["clientId"], $cred["clientSecret"], $cred["urlToken"]);

//inicia operação no banco de dados
 $conn->begin_transaction();

try {
    // Recebe os dados do front em JSON
    $input = json_decode(file_get_contents("php://input"), true);

    // Agrupa os dados recebidos
    $idUsuarioString = $input["idUsuario"] ?? "";
    $produtos = $input["produtos"] ?? [];
    $dadosPagamento = $input["dadosPagamento"] ?? [];
    $tipoPagamento = $dadosPagamento["cartao"];

    // converte o id do usuário em inteiro e valor total em centavos
    $idUsuario = converterId($idUsuarioString);
    $valor = converterValor($produtos);
    $reference = gerarReference();
    $payload = gerarPayload($dadosPagamento, $valor, $reference);

    // registra o pedido no banco de dados (status pendente)
    $resultadoPedido = criarPedido($conn, $idUsuario, $reference, $tipoPagamento, $valor);
    $pedido_id = $resultadoPedido["pedido_id"];

    $resultadoItensPedido = salvarItensPedido($conn, $pedido_id, $produtos);

    // Envia para a api da rede
    $resposta = chamarApi($token, $payload, $cred["urlTransacao"]);
    $respostaArray = json_decode($resposta, true);

//throw new Exception("FORCE ROLLBACK");
    $resultadoAtualizarPedido = atualizarPedido($conn, $respostaArray, $pedido_id);

    //se tudo certo, faz o commit
    $conn->commit();

    //testarResposta($resultadoAtualizarPedido);
    retornar($resposta);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["erro" => $e->getMessage()]);
}