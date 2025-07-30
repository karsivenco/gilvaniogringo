<?php
// Define o caminho do arquivo de rascunhos
$arquivo = 'rascunhos.json';

// Lê o conteúdo atual do arquivo ou cria um array vazio
$rascunhos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

// Lê os dados enviados via JSON
$input = json_decode(file_get_contents("php://input"), true);

// Valida os campos obrigatórios
if (!isset($input['titulo']) || !isset($input['data']) || !isset($input['texto'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Campos obrigatórios não preenchidos."]);
    exit;
}

// Cria o novo rascunho
$novoRascunho = [
    "titulo" => $input["titulo"],
    "data" => $input["data"],
    "resumo" => mb_strimwidth(strip_tags($input["texto"]), 0, 120, "..."),
    "link" => $input["link"] ?? ""
];

// Adiciona ao início do array de rascunhos
array_unshift($rascunhos, $novoRascunho);

// Salva no arquivo
if (file_put_contents($arquivo, json_encode($rascunhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(["sucesso" => true]);
} else {
    http_response_code(500);
    echo json_encode(["erro" => "Falha ao salvar o rascunho."]);
}
?>
