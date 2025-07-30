<?php
$data = [
  'titulo' => $_POST['titulo'] ?? '',
  'data' => $_POST['data'] ?? '',
  'link' => $_POST['link'] ?? '',
  'texto' => $_POST['texto'] ?? ''
];

// Salva no arquivo "postagens.json"
$arquivo = 'postagens.json';
$existente = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
$existente[] = $data;

file_put_contents($arquivo, json_encode($existente, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
http_response_code(200);
echo 'Postagem salva com sucesso!';
?>
