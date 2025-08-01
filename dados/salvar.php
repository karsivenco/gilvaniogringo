<?php
header("Content-Type: application/json");

// Caminho do arquivo onde as postagens serão salvas
$arquivo = 'dados/postagens.json';

// Garante que o diretório exista
if (!file_exists('dados')) {
    mkdir('dados', 0777, true);
}

// Coleta os dados do POST
$titulo = $_POST['titulo'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';
$data = $_POST['data'] ?? '';
$data_formatada = $_POST['data_formatada'] ?? '';

// Verificação básica
if (empty($titulo) || empty($texto)) {
    http_response_code(400);
    echo json_encode(["erro" => "Título e conteúdo são obrigatórios."]);
    exit;
}

// Monta a nova postagem
$nova_postagem = [
    "titulo" => $titulo,
    "link" => $link,
    "texto" => $texto,
    "data" => $data,
    "data_formatada" => $data_formatada
];

// Lê o conteúdo atual do arquivo
$postagens = [];
if (file_exists($arquivo)) {
    $conteudo = file_get_contents($arquivo);
    $postagens = json_decode($conteudo, true);
    if (!is_array($postagens)) {
        $postagens = [];
    }
}

// Adiciona nova postagem no topo
array_unshift($postagens, $nova_postagem);

// Salva o JSON novamente
file_put_contents($arquivo, json_encode($postagens, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo json_encode(["sucesso" => "Postagem salva com sucesso."]);
