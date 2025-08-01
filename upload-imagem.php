<?php
// upload-imagem.php

$diretorio = 'img/';

if (!file_exists($diretorio)) {
  mkdir($diretorio, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
  $arquivo = $_FILES['imagem'];
  
  if ($arquivo['error'] === 0) {
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nomeUnico = uniqid('img_', true) . '.' . $extensao;
    $caminho = $diretorio . $nomeUnico;

    if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
      echo $caminho;
    } else {
      http_response_code(500);
      echo "Erro ao mover o arquivo.";
    }
  } else {
    http_response_code(400);
    echo "Erro no envio do arquivo.";
  }
} else {
  http_response_code(405);
  echo "Método não permitido.";
}
?>
