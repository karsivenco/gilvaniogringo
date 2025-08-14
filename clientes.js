// clientes.js

// Função para buscar clientes na base
function pegarClientes() {
  return JSON.parse(localStorage.getItem("clientes")) || [];
}

// Pesquisa por nome
document.getElementById("pesquisaNome")?.addEventListener("input", function() {
  const termo = this.value.toLowerCase();
  const resultado = document.getElementById("resultadoPesquisa");
  resultado.innerHTML = "";

  const clientes = pegarClientes().filter(c => c.nome.toLowerCase().includes(termo));
  if (clientes.length === 0) {
    resultado.innerHTML = '<li class="py-2 text-gray-500 italic">Nenhum cliente encontrado.</li>';
    return;
  }

  clientes.forEach(c => {
    const li = document.createElement("li");
    li.className = "py-2";
    li.innerHTML = `
      <strong>${c.nome}</strong> - ${c.telefone} - ${c.municipio} - ${c.email || ''} - ${c.endereco || ''} - Nasc: ${c.dataNascimento || ''}
    `;
    resultado.appendChild(li);
  });
});

// Função para alternar abas
function mostrarAba(id) {
  document.querySelectorAll(".aba").forEach(el => el.classList.add("hidden"));
  document.getElementById(id).classList.remove("hidden");
}
