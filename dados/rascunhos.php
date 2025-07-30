<?php
header('Content-Type: application/json');

$arquivo = 'rascunhos.json'; // salva diretamente na raiz do projeto

// Verifica se os dados obrigatórios foram enviados
if (
    isset($_POST['titulo']) &&
    isset($_POST['link']) &&
    isset($_POST['texto']) &&
    isset($_POST['data']) &&
    isset($_POST['data_formatada'])
) {
    $rascunho = [
        'titulo' => $_POST['titulo'],
        'link' => $_POST['link'],
        'texto' => $_POST['texto'],
        'data' => $_POST['data'],
        'data_formatada' => $_POST['data_formatada'],
        'tipo' => 'rascunho'
    ];

    // Lê rascunhos anteriores (se existir)
    $rascunhos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

    // Adiciona o novo rascunho no início
    array_unshift($rascunhos, $rascunho);

    // Salva novamente o arquivo
    if (file_put_contents($arquivo, json_encode($rascunhos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['status' => 'ok']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao salvar no arquivo JSON.']);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}
?>
