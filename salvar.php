<?php
// salvar.php

session_start();

// Garante que apenas usuários logados possam salvar postagens
if (!isset($_SESSION['usuario'])) {
    http_response_code(403);
    echo 'Acesso negado';
    exit;
}

// Verifica se os dados necessários foram enviados
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
$texto = $_POST['texto']; // Mantemos o HTML do conteúdo
$data_iso = $_POST['data'] ?? date('c');
$data_formatada = $_POST['data_formatada'] ?? date('d/m/Y H:i');

// Estrutura do novo post
$post = [
    'titulo' => $titulo,
    'link' => $link,
    'texto' => $texto,
    'data' => $data_iso,
    'data_formatada' => $data_formatada
];

// Garante que a pasta 'dados' exista
$dir = __DIR__ . '/dados';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Caminho do arquivo JSON
$arquivo = $dir . '/postagens.json';

// Se o arquivo não existir, cria um vazio
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, '[]');
}

// Lê os posts existentes
$posts = json_decode(file_get_contents($arquivo), true);
if (!is_array($posts)) $posts = [];

// Adiciona o novo post no início da lista
array_unshift($posts, $post);

// Salva o novo conteúdo no JSON
if (file_put_contents($arquivo, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo "Postagem salva com sucesso!";
} else {
    http_response_code(500);
    echo "Erro ao salvar a postagem.";
}