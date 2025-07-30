<?php
// salvar.php - recebe dados via POST (JSON) e salva em postagens.json e atualiza ultimas-noticias.html

header('Content-Type: application/json');

$arquivo = 'postagens.json';
$paginaNoticias = 'ultimas-noticias.html';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['titulo']) || !isset($input['data']) || !isset($input['texto'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

$input['data_formatada'] = date('d/m/Y', strtotime($input['data']));

$postagens = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
array_unshift($postagens, $input);
file_put_contents($arquivo, json_encode($postagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Geração do HTML para ultimas-noticias.html
$html = file_get_contents($paginaNoticias);

$novaLista = "";
foreach ($postagens as $post) {
    $novaLista .= "<div class=\"w-full max-w-md border rounded-xl shadow-lg p-5 hover:shadow-xl transition text-center bg-white\">\n";
    $novaLista .= "  <h3 class=\"text-xl font-semibold text-[#005075] mb-2\">" . htmlspecialchars($post['titulo']) . "</h3>\n";
    $novaLista .= "  <p class=\"text-gray-600 text-sm mb-2\">" . $post['data_formatada'] . "</p>\n";
    $novaLista .= "  <p class=\"text-gray-700 mb-4\">" . nl2br(htmlspecialchars(substr($post['texto'], 0, 120))) . "...</p>\n";
    $novaLista .= "</div>\n";
}

// Substitui o conteúdo entre <div id="blog-posts">...</div> na página
$html = preg_replace(
    '/<div id="blog-posts" class="[^"]*">.*?<\/div>/s',
    '<div id="blog-posts" class="flex flex-wrap justify-center gap-6">' . $novaLista . '</div>',
    $html
);

file_put_contents($paginaNoticias, $html);

http_response_code(200);
echo json_encode(['status' => 'sucesso']);
exit;
