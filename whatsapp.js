let conversations = [];
let currentChat = null;

document.addEventListener("DOMContentLoaded", () => {
  loadConversations();
  setupSendMessage();
  document.getElementById("btnRefresh").addEventListener("click", loadConversations);
});

async function loadConversations() {
  const listEl = document.getElementById("contactsList");
  listEl.innerHTML = '<div class="text-gray-500 italic">Carregando...</div>';

  try {
    const data = await fetchChats();
    conversations = data.data.chats || [];
    renderConversations();
  } catch (err) {
    listEl.innerHTML = '<div class="text-red-500 italic">Erro ao carregar conversas.</div>';
    console.error(err);
  }
}

function renderConversations() {
  const listEl = document.getElementById("contactsList");
  listEl.innerHTML = "";
  if (!conversations.length) {
    listEl.innerHTML = '<div class="text-gray-500 italic">Nenhuma conversa encontrada.</div>';
    return;
  }

  conversations.forEach((chat, idx) => {
    const div = document.createElement("div");
    div.className = "contact-line p-2 rounded hover:bg-[#e0f2fe] flex justify-between items-center";
    div.innerHTML = `
      <span>${chat.name || chat.phone}</span>
      <span class="text-xs text-gray-400">${chat.last_message_timestamp ? formatDateTime(chat.last_message_timestamp) : ""}</span>
    `;
    div.addEventListener("click", () => selectConversation(idx));
    listEl.appendChild(div);
  });
}

async function selectConversation(idx) {
  currentChat = conversations[idx];
  document.getElementById("chatName").textContent = currentChat.name || currentChat.phone;
  document.getElementById("chatAvatar").src = currentChat.photo || "img/logo.png";
  document.getElementById("chatOrigin").textContent = currentChat.labels ? currentChat.labels.join(", ") : "-";
  document.getElementById("btnOpenExternal").href = currentChat.chat_url;

  document.getElementById("inputMessage").disabled = false;
  document.getElementById("btnSend").disabled = false;

  await loadMessages(currentChat.id);
}

async function loadMessages(chatId) {
  const area = document.getElementById("messagesArea");
  area.innerHTML = '<div class="text-gray-500 italic">Carregando mensagens...</div>';

  try {
    const data = await fetchMessages(chatId);
    renderMessages(data.data.messages || []);
  } catch (err) {
    area.innerHTML = '<div class="text-red-500 italic">Erro ao carregar mensagens.</div>';
    console.error(err);
  }
}

function renderMessages(messages) {
  const area = document.getElementById("messagesArea");
  area.innerHTML = "";

  if (!messages.length) {
    area.innerHTML = '<div class="text-gray-500 italic">Nenhuma mensagem ainda.</div>';
    return;
  }

  messages.forEach(msg => {
    const div = document.createElement("div");
    div.className = "flex flex-col";
    const bubble = document.createElement("div");
    bubble.className = `bubble ${msg.from_me ? "sent" : "recv"}`;
    bubble.textContent = msg.body;
    const ts = document.createElement("div");
    ts.className = "time-small ml-1";
    ts.textContent = formatDateTime(msg.timestamp);
    div.appendChild(bubble);
    div.appendChild(ts);
    area.appendChild(div);
  });

  area.scrollTop = area.scrollHeight;
}

function setupSendMessage() {
  const btn = document.getElementById("btnSend");
  const input = document.getElementById("inputMessage");

  btn.addEventListener("click", sendMessage);
  input.addEventListener("keydown", (e) => { if (e.key === "Enter") sendMessage(); });
}

async function sendMessage() {
  const input = document.getElementById("inputMessage");
  const text = input.value.trim();
  if (!text || !currentChat) return;

  appendLocalMessage({body: text, from_me: true, timestamp: new Date().toISOString()});
  input.value = "";

  try {
    await sendMessage(currentChat.id, text);
  } catch (err) {
    console.error("Erro ao enviar mensagem:", err);
    alert("Falha ao enviar mensagem.");
  }
}

function appendLocalMessage(msg) {
  const area = document.getElementById("messagesArea");
  const div = document.createElement("div");
  div.className = "flex flex-col";
  const bubble = document.createElement("div");
  bubble.className = `bubble ${msg.from_me ? "sent" : "recv"}`;
  bubble.textContent = msg.body;
  const ts = document.createElement("div");
  ts.className = "time-small ml-1";
  ts.textContent = formatDateTime(msg.timestamp);
  div.appendChild(bubble);
  div.appendChild(ts);
  area.appendChild(div);
  area.scrollTop = area.scrollHeight;
}
