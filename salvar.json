<?php
header('Content-Type: application/json');

$arquivo = 'postagens.json';
$paginaNoticias = 'ultimas-noticias.html';

// Receber dados via POST (FormData)
$titulo = $_POST['titulo'] ?? '';
$data = $_POST['data'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';

if (empty($titulo) || empty($data) || empty($texto)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

$post = [
    'titulo' => $titulo,
    'data' => $data,
    'link' => $link,
    'texto' => $texto,
    'data_formatada' => date('d/m/Y', strtotime(substr($data, 0, 10))) // considera que o início da string é uma data válida
];

// Carrega postagens antigas
$postagens = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
array_unshift($postagens, $post);

// Salva no JSON
if (!file_put_contents($arquivo, json_encode($postagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar no arquivo JSON.']);
    exit;
}

// Atualiza ultimas-noticias.html
$novaLista = "";
foreach ($postagens as $p) {
    $novaLista .= "<div class=\"w-full max-w-md border rounded-xl shadow-lg p-5 hover:shadow-xl transition text-center bg-white\">\n";
    $novaLista .= "  <h3 class=\"text-xl font-semibold text-[#005075] mb-2\">" . htmlspecialchars($p['titulo']) . "</h3>\n";
    $novaLista .= "  <p class=\"text-gray-600 text-sm mb-2\">" . $p['data_formatada'] . "</p>\n";
    $novaLista .= "  <p class=\"text-gray-700 mb-4\">" . mb_substr(strip_tags($p['texto']), 0, 120) . "...</p>\n";
    $novaLista .= "</div>\n";
}

// Substitui conteúdo da <div id="blog-posts"> em ultimas-noticias.html
$html = file_get_contents($paginaNoticias);
$html = preg_replace_callback(
    '/<div id="blog-posts"[^>]*>.*?<\/div>/s',
    fn($matches) => '<div id="blog-posts" class="flex flex-wrap justify-center gap-6">' . $novaLista . '</div>',
    $html
);

// Salva HTML atualizado
if (!file_put_contents($paginaNoticias, $html)) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar a página HTML.']);
    exit;
}

echo json_encode(['status' => 'sucesso']);
exit;
