<?php
// upload-image.php

// Recebe um arquivo de imagem e o grava no diretório 'img'
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nomeTmp = $_FILES['imagem']['tmp_name'];
    $nomeOriginal = $_FILES['imagem']['name'];

    // Pasta onde as imagens serão guardadas
    $diretorio = 'img';
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    // Cria um nome único para evitar sobrescritas
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
    $nomeUnico = uniqid('img_', true) . '.' . strtolower($extensao);
    $caminhoFinal = $diretorio . '/' . $nomeUnico;

    // Move o arquivo para a pasta designada
    if (move_uploaded_file($nomeTmp, $caminhoFinal)) {
        // Retorna o caminho relativo para ser usado no HTML
        echo $caminhoFinal;
    } else {
        http_response_code(500);
        echo 'Erro ao mover o arquivo.';
    }
} else {
    http_response_code(400);
    echo 'Nenhum arquivo enviado ou erro no envio.';
}