// menu.js
function logout() {
  localStorage.removeItem("usuarioLogado");
  window.location.href = "intranet.html";
}

function buildMenu() {
  const userLogged = localStorage.getItem("usuarioLogado");
  const menu = document.getElementById("mainMenu");
  menu.innerHTML = `
    <a href="painel.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition" title="Início">
      <i class="fas fa-home"></i> Início
    </a>
    <a href="cadastro.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition" title="Cadastro">
      <i class="fas fa-user-plus"></i> Cadastro
    </a>
    <a href="contatos.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition" title="Contatos">
      <i class="fas fa-address-book"></i> Contatos
    </a>
    <a href="perfil.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1">
      <i class="fas fa-user"></i> Perfil
    </a>
    ${userLogged === "gilvaniogringo" ? `
      <a href="dashboard.html" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition flex items-center gap-1">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
    ` : ""}
    <button onclick="logout()" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition flex items-center gap-1">
      <i class="fas fa-sign-out-alt"></i> Sair
    </button>
  `;
}

window.addEventListener("DOMContentLoaded", buildMenu);
