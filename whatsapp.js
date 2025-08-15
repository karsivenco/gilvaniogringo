// whatsapp.js
const API_BASE = "https://api.timelines.ai/v1";
const TOKEN = "ea8f1c30-23ad-438d-9fcf-a28942f0b413";

let chatsList = [];
let currentChat = null;

// -------------------- FUNÇÕES DE API --------------------
async function fetchAPI(endpoint, method = "GET", body = null) {
  const options = {
    method,
    headers: {
      "Authorization": `Bearer ${TOKEN}`,
      "Content-Type": "application/json",
    },
  };
  if (body) options.body = JSON.stringify(body);

  const res = await fetch(`${API_BASE}${endpoint}`, options);
  if (!res.ok) throw new Error("Erro ao acessar a API");
  return res.json();
}

async function getChats() {
  const res = await fetchAPI("/chats");
  chatsList = res.data || [];
  renderContactsList();
}

async function getChatDetails(chatId) {
  const res = await fetchAPI(`/chats/${chatId}`);
  return res.data;
}

async function sendMessage(chatId, message) {
  const body = { message };
  await fetchAPI(`/chats/${chatId}/messages`, "POST", body);
}

// -------------------- FUNÇÕES DE UI --------------------
function renderContactsList() {
  const contactsList = document.getElementById("contactsList");
  contactsList.innerHTML = "";

  if (chatsList.length === 0) {
    contactsList.innerHTML = '<div class="text-gray-500 text-sm">Nenhuma conversa encontrada.</div>';
    return;
  }

  chatsList.forEach(chat => {
    const div = document.createElement("div");
    div.className = "contact-line flex items-center gap-2 p-2 rounded hover:bg-gray-100 cursor-pointer";
    div.innerHTML = `
      <img src="${chat.photo || 'img/logo.png'}" class="h-8 w-8 rounded-full">
      <div class="flex-1">
        <div class="font-semibold">${chat.name}</div>
        <div class="text-xs text-gray-500">${chat.phone}</div>
      </div>
    `;
    div.addEventListener("click", () => selectChat(chat.id));
    contactsList.appendChild(div);
  });
}

async function selectChat(chatId) {
  currentChat = await getChatDetails(chatId);

  // Atualiza cabeçalho
  document.getElementById("chatName").textContent = currentChat.name;
  document.getElementById("chatAvatar").src = currentChat.photo || "img/logo.png";
  document.getElementById("chatOrigin").textContent = currentChat.is_group ? "Grupo" : currentChat.phone;
  document.getElementById("btnOpenExternal").href = currentChat.chat_url;

  // Habilita ou desabilita input
  document.getElementById("inputMessage").disabled = !currentChat.is_allowed_to_message;
  document.getElementById("btnSend").disabled = !currentChat.is_allowed_to_message;

  // Limpa histórico de mensagens
  const messagesArea = document.getElementById("messagesArea");
  messagesArea.innerHTML = `<div class="text-gray-500 italic">Nenhuma mensagem carregada.</div>`;

  // Exibe labels
  const chatHeader = document.getElementById("chatHeader");
  const existingBadges = chatHeader.querySelectorAll(".chat-badge, .chat-responsible");
  existingBadges.forEach(el => el.remove());

  currentChat.labels.forEach(label => {
    const badge = document.createElement("span");
    badge.textContent = label;
    badge.className = "ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full chat-badge";
    chatHeader.appendChild(badge);
  });

  // Responsável
  const respInfo = document.createElement("div");
  respInfo.textContent = `Responsável: ${currentChat.responsible_name} (${currentChat.responsible_email})`;
  respInfo.className = "text-sm text-gray-500 mt-1 chat-responsible";
  chatHeader.appendChild(respInfo);

  // Aqui você pode chamar função para carregar mensagens reais via API se disponível
}

// -------------------- ENVIO DE MENSAGEM --------------------
document.getElementById("btnSend").addEventListener("click", async () => {
  const input = document.getElementById("inputMessage");
  const message = input.value.trim();
  if (!message || !currentChat) return;

  // Adiciona a mensagem na tela
  const messagesArea = document.getElementById("messagesArea");
  const bubble = document.createElement("div");
  bubble.className = "bubble sent";
  bubble.innerHTML = `${message} <div class="time-small">${new Date().toLocaleTimeString()}</div>`;
  messagesArea.appendChild(bubble);
  messagesArea.scrollTop = messagesArea.scrollHeight;

  input.value = "";

  try {
    await sendMessage(currentChat.id, message);
  } catch (err) {
    console.error("Erro ao enviar mensagem:", err);
  }
});

// -------------------- BOTÃO ATUALIZAR --------------------
document.getElementById("btnRefresh").addEventListener("click", () => {
  getChats();
});

// -------------------- INICIALIZAÇÃO --------------------
window.addEventListener("DOMContentLoaded", () => {
  getChats();
});
