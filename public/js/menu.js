// public/js/menu.js
(function buildMenu(){
  const userLogged = localStorage.getItem('usuarioLogado');
  const menu = document.getElementById('mainMenu');
  if (!menu) return;
  menu.innerHTML = `
    <a href="painel.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition" title="Início">
      <i class="fas fa-home"></i> Início
    </a>
    <a href="cadastro.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition" title="Cadastro">
      <i class="fas fa-user-plus"></i> Cadastro
    </a>
    <a href="whatsapp" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition">
      <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    ${userLogged === 'gilvaniogringo' ? `
      <a href="dashboard.html" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition" title="Dashboard">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>` : ``}
    <button onclick="(function(){localStorage.removeItem('usuarioLogado'); location.href='intranet.html';})()"
      class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition">
      <i class="fas fa-sign-out-alt"></i> Sair
    </button>
  `;
})();
