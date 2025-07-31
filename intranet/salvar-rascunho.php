<?php
// salvar-rascunho.php

session_start();

// Apenas usuários autenticados podem salvar rascunhos
if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    echo 'Acesso negado';
    exit;
}

// Validação básica dos campos
if (
    !isset($_POST['titulo']) ||
    !isset($_POST['texto']) ||
    empty(trim($_POST['titulo'])) ||
    empty(trim($_POST['texto']))
) {
    http_response_code(400);
    echo "Título e texto são obrigatórios.";
    exit;
}

$titulo = strip_tags($_POST['titulo']);
$link = isset($_POST['link']) ? strip_tags($_POST['link']) : '';
$texto = $_POST['texto'];
$data_iso = $_POST['data'] ?? date('c');
$data_formatada = $_POST['data_formatada'] ?? date('d/m/Y H:i');

// Monta a estrutura do rascunho
$rascunho = [
    'titulo' => $titulo,
    'link' => $link,
    'texto' => $texto,
    'data' => $data_iso,
    'data_formatada' => $data_formatada
];

// Garante a existência da pasta de dados
$dir = __DIR__ . '/dados';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Caminho do arquivo onde os rascunhos serão gravados
$arquivo = $dir . '/rascunhos.json';

// Se o arquivo não existir, cria um array vazio
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, '[]');
}

// Lê os rascunhos existentes
$rascunhos = json_decode(file_get_contents($arquivo), true);
if (!is_array($rascunhos)) $rascunhos = [];

// Adiciona o novo rascunho no início
array_unshift($rascunhos, $rascunho);

// Persiste os rascunhos no arquivo
if (file_put_contents($arquivo, json_encode($rascunhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo "Rascunho salvo com sucesso!";
} else {
    http_response_code(500);
    echo "Erro ao salvar o rascunho.";
}