// transmissao.js

function montarListaTransmissao() {
  const container = document.getElementById("listaPorRegiao");
  if (!container) return;

  const clientes = JSON.parse(localStorage.getItem("clientes")) || [];

  // Agrupar por município
  const agrupados = {};
  clientes.forEach(c => {
    const reg = c.municipio || "Sem Município";
    if (!agrupados[reg]) agrupados[reg] = [];
    agrupados[reg].push(c);
  });

  container.innerHTML = "";

  for (const regiao in agrupados) {
    const divReg = document.createElement("div");
    divReg.className = "mb-4";

    divReg.innerHTML = `<h4 class="font-bold text-[#09679c] mb-2">${regiao} (${agrupados[regiao].length})</h4>`;
    const ul = document.createElement("ul");
    ul.className = "divide-y divide-gray-200";

    agrupados[regiao].forEach(c => {
      const li = document.createElement("li");
      li.className = "py-2";
      li.textContent = `${c.nome} - ${c.bairro || ''} - ${c.telefone} - ${c.email || ''} - ${c.endereco || ''}`;
      ul.appendChild(li);
    });

    divReg.appendChild(ul);
    container.appendChild(divReg);
  }
}

window.addEventListener("DOMContentLoaded", montarListaTransmissao);
