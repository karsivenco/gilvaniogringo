<?php
header('Content-Type: application/json');

$arquivo = 'rascunhos.json';

// Receber dados do formulário via POST
$titulo = $_POST['titulo'] ?? '';
$data = $_POST['data'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';

if (empty($titulo) || empty($data) || empty($texto)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

// Cria estrutura do rascunho
$rascunho = [
    'titulo' => $titulo,
    'data' => $data,
    'link' => $link,
    'texto' => $texto,
    'data_formatada' => date('d/m/Y', strtotime(substr($data, 0, 10)))
];

// Lê o arquivo de rascunhos existente
$rascunhos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

// Adiciona no início da lista
array_unshift($rascunhos, $rascunho);

// Salva o novo arquivo de rascunhos
if (!file_put_contents($arquivo, json_encode($rascunhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar rascunho.']);
    exit;
}

http_response_code(200);
echo json_encode(['status' => 'rascunho_salvo']);
exit;
