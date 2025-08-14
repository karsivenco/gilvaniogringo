// aniversario.js

const meses = [
  "Janeiro","Fevereiro","Março","Abril","Maio","Junho",
  "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"
];

function pegarClientes() {
  return JSON.parse(localStorage.getItem("clientes")) || [];
}

function montarCalendario() {
  const container = document.getElementById("calendarioMeses");
  container.innerHTML = "";
  meses.forEach((mes, idx) => {
    const count = pegarClientes().filter(c => {
      if (!c.dataNascimento) return false;
      return new Date(c.dataNascimento).getMonth() === idx;
    }).length;

    const div = document.createElement("div");
    div.className = "bg-gray-100 p-3 rounded text-center cursor-pointer hover:bg-gray-200";
    div.innerHTML = `<strong>${mes}</strong><br>${count} pessoa(s)`;
    div.onclick = () => mostrarAniversariantesMes(idx);
    container.appendChild(div);
  });
}

function mostrarAniversariantesMes(mesIdx) {
  const lista = document.getElementById("listaAniversariantes");
  lista.innerHTML = "";
  const clientes = pegarClientes().filter(c => c.dataNascimento && new Date(c.dataNascimento).getMonth() === mesIdx);

  if (clientes.length === 0) {
    lista.innerHTML = '<li class="py-2 text-gray-500 italic">Nenhum aniversariante neste mês.</li>';
    return;
  }

  clientes.forEach(c => {
    const li = document.createElement("li");
    li.className = "py-2";
    li.innerHTML = `
      <strong>${c.nome}</strong> - ${c.dataNascimento} - ${c.municipio} - ${c.telefone} - ${c.email || ''} - ${c.endereco || ''}
    `;
    lista.appendChild(li);
  });
}

// Botão enviar (simulado)
document.getElementById("btnEnviarAniversario")?.addEventListener("click", () => {
  alert("Mensagens de aniversário enviadas para a lista de transmissão!");
});

// Inicializar calendário
window.addEventListener("DOMContentLoaded", montarCalendario);
