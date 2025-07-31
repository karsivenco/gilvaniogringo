// scripts/listar-rascunhos.js

function carregarRascunhos() {
  fetch('dados/rascunhos.json')
    .then(res => res.json())
    .then(drafts => {
      const container = document.getElementById('blog-drafts');
      container.className = 'flex flex-wrap justify-center gap-6';
      if (!Array.isArray(drafts) || drafts.length === 0) {
        container.innerHTML = '<p class="text-gray-600 text-center">Nenhum rascunho encontrado.</p>';
        return;
      }
      container.innerHTML = drafts.map(post => {
        const data = new Date(post.data).toLocaleDateString('pt-BR');
        const resumo = post.texto.replace(/<[^>]+>/g, '').slice(0, 120);
        const link = post.link ? `<a href="${post.link}" class="text-[#005075] font-medium hover:underline" target="_blank">Ver mais</a>` : '';
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
      document.getElementById('blog-drafts').innerHTML = '<p class="text-red-600 text-center">Erro ao carregar rascunhos.</p>';
    });
}

document.addEventListener('DOMContentLoaded', carregarRascunhos);
