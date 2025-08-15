// public/js/timelinesClient.js
const API = {
  async listChats(page = 1) {
    const res = await fetch(`/api/chats?page=${encodeURIComponent(page)}`);
    if (!res.ok) throw new Error('Erro ao listar chats');
    return res.json(); // {status,data:{chats, has_more_pages}}
  },

  async getChatMessages(chatId, params = {}) {
    const qs = new URLSearchParams(params).toString();
    const res = await fetch(`/api/chats/${encodeURIComponent(chatId)}/messages${qs ? `?${qs}` : ''}`);
    if (!res.ok) throw new Error('Erro ao carregar mensagens');
    return res.json(); // {status,data:{messages:[...]}}
  },

  async sendChatMessage(chatId, text) {
    const res = await fetch(`/api/chats/${encodeURIComponent(chatId)}/messages`, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({ text })
    });
    if (!res.ok) throw new Error('Erro ao enviar mensagem');
    return res.json(); // {status,data:{...}}
  }
};

window.TimelinesAPI = API;
