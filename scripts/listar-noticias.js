// scripts/listar-noticias.js

function carregarNoticias() {
  fetch('dados/postagens.json')
    .then(res => res.json())
    .then(posts => {
      const container = document.getElementById('blog-posts');
      container.className = 'flex flex-wrap justify-center gap-6';
      if (!Array.isArray(posts) || posts.length === 0) {
        container.innerHTML = '<p class="text-gray-600 text-center">Nenhuma notícia encontrada.</p>';
        return;
      }
      container.innerHTML = posts.map(post => {
        const data = new Date(post.data).toLocaleDateString('pt-BR');
        const resumo = post.texto.replace(/<[^>]+>/g, '').slice(0, 120);
        const link = post.link ? `<a href="${post.link}" class="text-[#005075] font-medium hover:underline" target="_blank">Leia mais</a>` : '';
        return `
          <div class="w-full max-w-md border rounded-xl shadow-lg p-5 hover:shadow-xl transition text-center bg-white">
            <h3 class="text-xl font-semibold text-[#005075] mb-2">${post.titulo}</h3>
            <p class="text-gray-600 text-sm mb-2">${data}</p>
            <p class="text-gray-700 mb-4">${resumo}...</p>
            ${link}
          </div>`;
      }).join('');
    })
    .catch(() => {
      document.getElementById('blog-posts').innerHTML = '<p class="text-red-600 text-center">Erro ao carregar notícias.</p>';
    });
}

document.addEventListener('DOMContentLoaded', carregarNoticias);

