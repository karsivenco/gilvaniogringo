<?php
// Caminho para o arquivo de postagens
$arquivo = 'dados/postagens.json';

// Garante que o diretório existe
if (!file_exists('dados')) {
    mkdir('dados', 0777, true);
}

// Coleta os dados enviados via POST
$titulo = $_POST['titulo'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';
$data = $_POST['data'] ?? '';
$data_formatada = $_POST['data_formatada'] ?? '';

// Verifica campos obrigatórios
if (empty($titulo) || empty($texto)) {
    http_response_code(400);
    echo "Título e conteúdo são obrigatórios.";
    exit;
}

// Cria o novo item
$nova_postagem = [
    'titulo' => $titulo,
    'link' => $link,
    'texto' => $texto,
    'data' => $data,
    'data_formatada' => $data_formatada
];

// Lê o conteúdo atual
$lista = [];
if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $lista = json_decode($json, true) ?? [];
}

// Adiciona no topo
array_unshift($lista, $nova_postagem);

// Salva novamente
file_put_contents($arquivo, json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Postagem salva com sucesso.";
