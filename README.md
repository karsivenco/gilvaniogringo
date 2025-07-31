diff --git a//dev/null b/README.md
index 0000000000000000000000000000000000000000..5ec3b45a64aab7883ceadbccd0ad8546da0d60c6 100644
--- a//dev/null
+++ b/README.md
@@ -0,0 +1,23 @@
+# Intranet do Gringo
+
+Este repositório contém a página oficial e a intranet simples utilizada para publicar notícias.
+
+## Como utilizar
+
+1. Instale um servidor web com suporte a PHP (ex: Apache ou Nginx).
+2. Coloque os arquivos em um diretório acessível pelo servidor.
+3. Acesse `intranet.html` para fazer login.
+   - Usuários atuais:
+     - **graziele.albuquerque** / **Gringo1975**
+     - **gabriel.amaral** / **Gringo1975**
+4. Após o login é possível criar novas postagens em "Nova Postagem".
+5. As postagens são armazenadas em `dados/postagens.json` e são listadas em `ultimas-noticias.html`.
+
+As postagens e rascunhos são salvos em arquivos JSON localmente, sem banco de dados.
+
+## Estrutura dos dados
+
+- `dados/postagens.json` guarda todas as notícias publicadas.
+- `dados/rascunhos.json` armazena rascunhos criados na página de nova postagem.
+
+A página `ultimas-noticias.html` carrega automaticamente os itens de `postagens.json` utilizando o script `scripts/listar-noticias.js`.
