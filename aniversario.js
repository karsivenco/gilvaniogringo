const meses = [
  "Janeiro","Fevereiro","Março","Abril","Maio","Junho",
  "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"
];

function pegarClientes() {
  return JSON.parse(localStorage.getItem("clientes")) || [];
}

function montarCalendario() {
  const container = document.getElementById("aniversariantesMes");
  container.innerHTML = "";
  meses.forEach((mes) => {
    const count = pegarClientes().filter(c => c.mesAniversario === mes).length;
    const div = document.createElement("div");
    div.className = "bg-gray-100 p-3 rounded text-center cursor-pointer hover:bg-gray-200";
    div.innerHTML = `<strong>${mes}</strong><br>${count} pessoa(s)`;
    div.onclick = () => mostrarAniversariantesMes(mes);
    container.appendChild(div);
  });
}

function mostrarAniversariantesMes(mes) {
  const lista = document.getElementById("listaAniversariantes");
  lista.innerHTML = "";

  const clientes = pegarClientes().filter(c => c.mesAniversario === mes);

  if (clientes.length === 0) {
    lista.innerHTML = '<li class="py-2 text-gray-500 italic">Nenhum aniversariante neste mês.</li>';
    return;
  }

  clientes.forEach(c => {
    const li = document.createElement("li");
    li.className = "py-2";
    li.innerHTML = `
      <strong>${c.nome}</strong> - ${c.municipio} - ${c.bairro || ''} - ${c.numero || ''} - ${c.endereco || ''}
    `;
    lista.appendChild(li);
  });
}

window.addEventListener("DOMContentLoaded", montarCalendario);
