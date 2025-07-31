<?php
// upload-image.php

// Este script recebe um arquivo enviado pelo editor de postagens e grava
// dentro da pasta 'img'. O caminho resultante é retornado em texto plano
// para que o front-end possa inserir a imagem na postagem.

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nomeTmp = $_FILES['imagem']['tmp_name'];
    $nomeOriginal = $_FILES['imagem']['name'];

    // Cria o diretório de imagens se não existir
    $diretorio = 'img';
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    // Gera um nome único para evitar sobrescritas
    $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
    $nomeUnico = uniqid('img_', true) . '.' . strtolower($extensao);

    $caminhoFinal = $diretorio . '/' . $nomeUnico;

    // Move o arquivo para a pasta 'img'
    if (move_uploaded_file($nomeTmp, $caminhoFinal)) {
        // Retorna o caminho relativo para uso no HTML
        echo $caminhoFinal;
    } else {
        http_response_code(500);
        echo 'Erro ao mover o arquivo.';
    }
} else {
    http_response_code(400);
    echo 'Nenhum arquivo enviado ou erro no envio.';
}