diff --git a/dados/salvar.php b/dados/salvar.php
index 6de42cb0a163d0afb88bdc0ae9967be2ac94eea9..2a2036026906b1584de07fbe86235d6ac104952c 100644
--- a/dados/salvar.php
+++ b/dados/salvar.php
@@ -28,30 +28,26 @@ $post = [
     'data_formatada' => $data_formatada
 ];
 
 // Caminho do arquivo JSON
 $arquivo = __DIR__ . '/postagens.json';
 
 // Se o arquivo não existir, cria um vazio
 if (!file_exists($arquivo)) {
     file_put_contents($arquivo, '[]');
 }
 
 // Lê os posts existentes
 $posts = json_decode(file_get_contents($arquivo), true);
 if (!is_array($posts)) $posts = [];
 
 // Adiciona o novo post no início da lista
 array_unshift($posts, $post);
 
 // Salva o novo conteúdo no JSON
 if (file_put_contents($arquivo, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
     echo "Postagem salva com sucesso!";
 } else {
     http_response_code(500);
     echo "Erro ao salvar a postagem.";
 }
-fetch("salvar.php", {
-  method: "POST",
-  body: formData
-})
 
