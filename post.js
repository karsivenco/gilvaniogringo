export function gerarHtmlPostCompleto(post) {
  return `<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>${post.titulo}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    crossorigin="anonymous"
  />
  <style>
    body { background-color: #f9fafb; font-family: Arial, sans-serif; padding: 20px; }
    h1 { color: #09679c; }
    .content {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
      max-width: 800px;
      margin: auto;
    }
    a { color: #09679c; }
  </style>
</head>
<body>
  <div class="content">
    <h1>${post.titulo}</h1>
    <p><em>Publicado em: ${new Date(post.data).toLocaleDateString('pt-BR', { day:'2-digit', month:'2-digit', year:'numeric' })}</em></p>
    <div>${post.conteudo}</div>
    <p><a href="publicacoes.html">&larr; Voltar às publicações</a></p>
  </div>
</body>
</html>`;
}
