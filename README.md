# Intranet do Gringo

Este repositório contém a página oficial e a intranet simples utilizada para publicar notícias.

## Como utilizar

1. Instale um servidor web com suporte a PHP (ex: Apache ou Nginx).
2. Coloque os arquivos em um diretório acessível pelo servidor.
3. Acesse `intranet.html` para fazer login.
   - Usuários atuais:
     - **graziele.albuquerque** / **Gringo1975**
     - **gabriel.amaral** / **Gringo1975**
   - O acesso ao painel é protegido por sessão PHP.
4. Após o login é possível criar novas postagens em "Nova Postagem".
5. As postagens são armazenadas em `dados/posts.json` e são listadas em `ultimas-noticias.html`.
   - O editor possui botões para **negrito**, listas, emojis e envio de imagens.

As postagens e rascunhos são salvos em arquivos JSON localmente, sem banco de dados.

## Estrutura dos dados

- `dados/posts.json` guarda todas as notícias publicadas.
- `dados/rascunhos.json` armazena rascunhos criados na página de nova postagem.

A página `ultimas-noticias.html` carrega automaticamente os itens de `posts.json` utilizando o script `scripts/listar-noticias.js`.
Já a página `rascunho.html` lista os rascunhos presentes em `dados/rascunhos.json` com auxílio do script `scripts/listar-rascunhos.js`.
