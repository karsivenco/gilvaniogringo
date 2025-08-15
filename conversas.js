// conversas.js
async function listarConversas() {
  try {
    const res = await fetch(`${API_BASE}/whatsapp/chats`, {
      headers: {
        "Authorization": `Bearer ${API_KEY}`
      }
    });
    const data = await res.json();

    if (data.status === "ok") {
      renderConversas(data.data.chats);
    } else {
      console.error("Erro ao buscar conversas:", data);
    }
  } catch (err) {
    console.error("Falha na requisição:", err);
  }
}

function renderConversas(chats) {
  const container = document.getElementById("listaConversas");
  container.innerHTML = "";

  chats.forEach(chat => {
    const div = document.createElement("div");
    div.className = "chat-item";
    div.innerHTML = `
      <img src="${chat.photo}" alt="Foto" class="chat-photo">
      <div class="chat-info">
        <h4>${chat.name} <small>${chat.phone}</small></h4>
        <p>Última msg: ${chat.last_message_timestamp}</p>
        <a href="${chat.chat_url}" target="_blank">Abrir no Timelines</a>
      </div>
    `;
    container.appendChild(div);
  });
}

window.addEventListener("DOMContentLoaded", listarConversas);
