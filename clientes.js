// clientes.js

// Base de clientes
const clientes = JSON.parse(localStorage.getItem("clientes")) || [];
const meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];

// ---------- Abas ----------
function mostrarAba(id) {
  document.querySelectorAll('.aba').forEach(el => el.classList.add('hidden'));
  document.getElementById(id).classList.remove('hidden');
}

// ---------- Pesquisa Dados Pessoais ----------
document.getElementById("pesquisaNome")?.addEventListener("input", e => {
  const termo = e.target.value.toLowerCase();
  const resultado = clientes.filter(c => c.nome.toLowerCase().includes(termo));
  const ul = document.getElementById("resultadoPesquisa");
  ul.innerHTML = "";

  if(resultado.length === 0) {
    ul.innerHTML = "<li class='py-2 text-gray-500 italic'>Nenhum cliente encontrado.</li>";
    return;
  }

  resultado.forEach(c => {
    const li = document.createElement("li");
    li.className = "py-2";
    li.innerHTML = `
      <strong>${c.nome}</strong> - ${c.dataNascimento ? new Date(c.dataNascimento).toLocaleDateString() : ''}<br>
      Município: ${c.municipio} ${c.bairro ? '- ' + c.bairro : ''}<br>
      Telefone: ${c.telefone} - Email: ${c.email || ''}<br>
      Endereço: ${c.endereco || ''}
    `;
    ul.appendChild(li);
  });
});

// ---------- Aniversários ----------
function contarAniversariantesPorMes() {
  const contagem = Array(12).fill(0);
  clientes.forEach(c => {
    if(c.dataNascimento) {
      const mes = new Date(c.dataNascimento).getMonth();
      contagem[mes]++;
    }
  });
  return contagem;
}

function montarCalendarioMeses() {
  const contagem = contarAniversariantesPorMes();
  const calendario = document.getElementById("calendarioMeses");
  calendario.innerHTML = "";

  meses.forEach((nomeMes, i) => {
    const div = document.createElement("div");
    div.className = "p-3 bg-gray-100 rounded hover:bg-gray-200 cursor-pointer text-center";
    div.innerHTML = `<strong>${nomeMes}</strong><br>${contagem[i]} pessoas`;
    div.onclick = () => mostrarAniversariantesDoMes(i);
    calendario.appendChild(div);
  });
}

function mostrarAniversariantesDoMes(mes) {
  const listaEl = document.getElementById("listaAniversariantes");
  listaEl.innerHTML = "";

  const aniversariantes = clientes.filter(c => c.dataNascimento && new Date(c.dataNascimento).getMonth() === mes);

  if(aniversariantes.length === 0) {
    listaEl.innerHTML = "<li class='py-2 text-gray-500 italic'>Nenhum aniversariante neste mês.</li>";
    return;
  }

  aniversariantes.forEach(c => {
    const li = document.createElement("li");
    li.className = "py-2";
    li.innerHTML = `
      <strong>${c.nome}</strong> - ${new Date(c.dataNascimento).toLocaleDateString()}<br>
      Município: ${c.municipio} ${c.bairro ? '- ' + c.bairro : ''}<br>
      Telefone: ${c.telefone} - Email: ${c.email || ''}<br>
      Endereço: ${c.endereco || ''}
    `;
    listaEl.appendChild(li);
  });
}

// ---------- Enviar aniversariantes para Lista de Transmissão ----------
document.getElementById("btnEnviarAniversario")?.addEventListener("click", () => {
  const lista = Array.from(document.getElementById("listaAniversariantes").children)
    .map(li => li.textContent);
  
  if(lista.length === 0) return alert("Nenhum aniversariante selecionado.");
  
  let listaTransmissao = JSON.parse(localStorage.getItem("listaTransmissao")) || [];
  listaTransmissao.push(...lista);
  localStorage.setItem("listaTransmissao", JSON.stringify(listaTransmissao));
  alert("Aniversariantes enviados para a lista de transmissão!");
});

// ---------- Inicialização ----------
window.addEventListener("DOMContentLoaded", montarCalendarioMeses);
