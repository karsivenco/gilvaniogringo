<?php
header('Content-Type: application/json');

$arquivo = 'postagens.json';
$paginaNoticias = 'ultimas-noticias.html';

// Receber dados via POST
$titulo = $_POST['titulo'] ?? '';
$link = $_POST['link'] ?? '';
$texto = $_POST['texto'] ?? '';
$data = $_POST['data'] ?? '';
$data_formatada = $_POST['data_formatada'] ?? '';

if (!$titulo || !$texto || !$data || !$data_formatada) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

$postagens = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
array_unshift($postagens, [
    'titulo' => $titulo,
    'link' => $link,
    'texto' => $texto,
    'data' => $data,
    'data_formatada' => $data_formatada,
    'tipo' => 'publicado'
]);

if (!file_put_contents($arquivo, json_encode($postagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar a postagem.']);
    exit;
}

// Atualizar ultimas-noticias.html
$html = file_get_contents($paginaNoticias);
if (!$html) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao carregar a página de notícias.']);
    exit;
}

$novaLista = "";
foreach ($postagens as $p) {
    $novaLista .= "<div class=\"w-full max-w-md border rounded-xl shadow-lg p-5 hover:shadow-xl transition text-center bg-white\">\n";
    $novaLista .= "<h3 class=\"text-xl font-semibold text-[#005075] mb-2\">" . htmlspecialchars($p['titulo']) . "</h3>\n";
    $novaLista .= "<p class=\"text-gray-600 text-sm mb-2\">" . htmlspecialchars($p['data_formatada']) . "</p>\n";
    $novaLista .= "<p class=\"text-gray-700 mb-4\">" . nl2br(htmlspecialchars(strip_tags(substr($p['texto'], 0, 150)))) . "...</p>\n";
    $novaLista .= "</div>\n";
}

$html = preg_replace(
    '/<div id="blog-posts"[^>]*>.*?<\/div>/s',
    '<div id="blog-posts" class="flex flex-wrap justify-center gap-6">' . $novaLista . '</div>',
    $html
);

if (!file_put_contents($paginaNoticias, $html)) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao atualizar a página de notícias.']);
    exit;
}

echo json_encode(['status' => 'ok']);
