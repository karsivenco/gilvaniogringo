<?php
header("Content-Type: text/plain");

// Caminho da pasta de destino
$diretorio = "img/uploads/";

// Cria o diretório se não existir
if (!file_exists($diretorio)) {
    mkdir($diretorio, 0777, true);
}

// Verifica se foi enviada uma imagem
if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] != 0) {
    http_response_code(400);
    echo "Erro no envio da imagem.";
    exit;
}

// Valida extensão
$permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $permitidos)) {
    http_response_code(400);
    echo "Extensão não permitida.";
    exit;
}

// Gera nome único
$nome_arquivo = uniqid('img_', true) . "." . $ext;
$caminho_completo = $diretorio . $nome_arquivo;

// Move o arquivo
if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
    // Retorna a URL para uso no editor
    echo $caminho_completo;
} else {
    http_response_code(500);
    echo "Erro ao salvar a imagem.";
}
