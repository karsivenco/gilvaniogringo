<?php
header('Content-Type: application/json');

$arquivo = 'rascunhos.json';

$titulo = $_POST['titulo'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';
$data = $_POST['data'] ?? '';
$data_formatada = $_POST['data_formatada'] ?? '';

if (!$titulo || !$texto || !$data || !$data_formatada) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos para rascunho.']);
    exit;
}

$rascunhos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
array_unshift($rascunhos, [
    'titulo' => $titulo,
    'link' => $link,
    'texto' => $texto,
    'data' => $data,
    'data_formatada' => $data_formatada,
    'tipo' => 'rascunho'
]);

if (!file_put_contents($arquivo, json_encode($rascunhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar rascunho.']);
    exit;
}

echo json_encode(['status' => 'ok']);
