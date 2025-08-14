// mensagens.js

const eventos = [
  "Natal","Ano Novo","Páscoa","7 de Setembro","20 de Setembro",
  "Dia dos Pais","Dia das Mães","Dia do Amigo"
];

function montarMensagensProntas() {
  const container = document.getElementById("eventosMensagens");
  container.innerHTML = "";

  eventos.forEach(evento => {
    const div = document.createElement("div");
    div.className = "flex justify-between items-center p-2 bg-gray-100 rounded hover:bg-gray-200";
    div.innerHTML = `
      <span>${evento}</span>
      <button class="bg-[#09679c] text-white px-3 py-1 rounded hover:bg-[#074d6b] transition" onclick="enviarMensagem('${evento}')">
        Enviar
      </button>
    `;
    container.appendChild(div);
  });
}

function enviarMensagem(evento) {
  alert(`Mensagem de ${evento} enviada para a lista de transmissão!`);
}

window.addEventListener("DOMContentLoaded", montarMensagensProntas);
