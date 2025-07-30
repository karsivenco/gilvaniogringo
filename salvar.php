<?php
// salvar.php - recebe dados via POST (JSON) e salva em postagens.json

header('Content-Type: application/json');

// Caminho do arquivo onde as postagens serão salvas
$arquivo = 'postagens.json';

// Verifica se o corpo da requisição está vazio
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['titulo']) || !isset($input['data']) || !isset($input['texto'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

// Adiciona data formatada para exibição
$input['data_formatada'] = date('d/m/Y', strtotime($input['data']));

// Lê o arquivo existente ou cria um novo
$postagens = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

// Adiciona a nova postagem no topo do array
array_unshift($postagens, $input);

// Salva o JSON atualizado
file_put_contents($arquivo, json_encode($postagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Resposta de sucesso
http_response_code(200);
echo json_encode(['status' => 'sucesso']);
exit;
