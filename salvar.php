<?php
// salvar.php - publica a nova postagem em postagens.json e atualiza a div no ultimas-noticias.html

header('Content-Type: application/json');

$arquivo = 'postagens.json';
$paginaNoticias = 'ultimas-noticias.html';

// Receber dados JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['titulo']) || !isset($input['data']) || !isset($input['texto'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

$input['data_formatada'] = date('d/m/Y', strtotime($input['data']));

// Salvar no JSON
$postagens = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
array_unshift($postagens, $input);
if (!file_put_contents($arquivo, json_encode($postagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar no arquivo JSON.']);
    exit;
}

// Gerar HTML dos cards
$novaLista = "";
foreach ($postagens as $post) {
    $novaLista .= "<div class=\"w-full max-w-md border rounded-xl shadow-lg p-5 hover:shadow-xl transition text-center bg-white\">\n";
    $novaLista .= "  <h3 class=\"text-xl font-semibold text-[#005075] mb-2\">" . htmlspecialchars($post['titulo']) . "</h3>\n";
    $novaLista .= "  <p class=\"text-gray-600 text-sm mb-2\">" . $post['data_formatada'] . "</p>\n";
    $novaLista .= "  <p class=\"text-gray-700 mb-4\">" . nl2br(htmlspecialchars(substr($post['texto'], 0, 120))) . "...</p>\n";
    $novaLista .= "</div>\n";
}

// Carregar HTML da página
$html = file_get_contents($paginaNoticias);
if (!$html) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao carregar a página HTML.']);
    exit;
}

// Substituir a div inteira por nova lista de cards (div multilinha)
$html = preg_replace_callback(
    '/<div id="blog-posts"[^>]*>(.*?)<\/div>/s',
    function($matches) use ($novaLista) {
        return '<div id="blog-posts" class="flex flex-wrap justify-center gap-6">' . $novaLista . '</div>';
    },
    $html
);

// Salvar HTML atualizado
if (!file_put_contents($paginaNoticias, $html)) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar a página HTML.']);
    exit;
}

http_response_code(200);
echo json_encode(['status' => 'sucesso']);
exit;
