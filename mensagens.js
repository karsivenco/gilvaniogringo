// mensagens.js

const eventos = [
  {nome: "Bom Dia", img: "img/bomDia.png"},
  {nome: "Boa Tarde", img: "img/boaTarde.png"},
  {nome: "Boa Noite", img: "img/boaNoite.png"},
  {nome: "Natal", img: "img/natal.png"},
  {nome: "Ano Novo", img: "img/anoNovo.png"},
  {nome: "Páscoa", img: "img/pascoa.png"},
  {nome: "7 de Setembro", img: "img/7setembro.png"},
  {nome: "20 de Setembro", img: "img/20setembro.png"},
  {nome: "Dia das Mães", img: "img/maes.png"},
  {nome: "Dia dos Pais", img: "img/pais.png"},
  {nome: "Dia do Amigo", img: "img/amigo.png"}
];

function montarMensagens() {
  const container = document.getElementById("mensagens");
  if (!container) return;

  const grid = container.querySelector("div.grid");
  grid.innerHTML = "";

  eventos.forEach(evento => {
    const div = document.createElement("div");
    div.className = "bg-gray-100 p-3 rounded shadow flex flex-col items-center";
    div.innerHTML = `
      <img src="${evento.img}" alt="${evento.nome}" class="h-20 mb-2"/>
      <strong>${evento.nome}</strong>
      <textarea class="border p-2 mt-2 w-full" placeholder="Mensagem para ${evento.nome}..."></textarea>
      <button class="bg-[#09679c] text-white px-4 py-2 mt-2 rounded hover:bg-[#074d6b] transition">Enviar</button>
    `;
    grid.appendChild(div);
  });
}

window.addEventListener("DOMContentLoaded", montarMensagens);
