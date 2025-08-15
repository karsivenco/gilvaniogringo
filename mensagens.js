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
      <button class="bg-[#09679c] text-white px-4 py-2 mt-2 rounded hover:bg-[#074d6b] transition">
        Enviar
      </button>
    `;
    const botao = div.querySelector("button");
    botao.addEventListener("click", () => {
      const municipio = document.getElementById("mensagemMunicipio").value;
      const texto = div.querySelector("textarea").value;
      if (!municipio) {
        alert("Selecione um município para enviar.");
        return;
      }
      if (!texto) {
        alert("Digite a mensagem antes de enviar.");
        return;
      }
      // Aqui você pode integrar com envio real
      alert(`Mensagem "${texto}" enviada para ${municipio}.`);
    });

    grid.appendChild(div);
  });
}

// Preenche select de municípios (reaproveitando array municipios.js)
function popularSelectMunicipios() {
  const select = document.getElementById("mensagemMunicipio");
  if (!select || typeof municipios === "undefined") return;

  municipios.forEach(m => {
    const option = document.createElement("option");
    option.value = m;
    option.textContent = m;
    select.appendChild(option);
  });
}

window.addEventListener("DOMContentLoaded", () => {
  popularSelectMunicipios();
  montarMensagens();
});
