<?php
header('Content-Type: application/json');

$arquivo = 'postagens.json'; // Salva no mesmo diretório

// Validação dos dados
$titulo = $_POST['titulo'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';
$data = $_POST['data'] ?? '';
$data_formatada = $_POST['data_formatada'] ?? '';

if (!$titulo || !$texto || !$data || !$data_formatada) {
  http_response_code(400);
  echo json_encode(['erro' => 'Dados incompletos']);
  exit;
}

// Lê conteúdo atual
$existentes = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

// Novo conteúdo
$nova_postagem = [
  'titulo' => $titulo,
  'link' => $link,
  'texto' => $texto,
  'data' => $data,
  'data_formatada' => $data_formatada
];

// Adiciona no topo da lista
array_unshift($existentes, $nova_postagem);

// Salva de volta
if (file_put_contents($arquivo, json_encode($existentes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
  echo json_encode(['status' => 'ok']);
} else {
  http_response_code(500);
  echo json_encode(['erro' => 'Falha ao salvar']);
}
