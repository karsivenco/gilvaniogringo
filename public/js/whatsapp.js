// public/js/whatsapp.js
import { el, safeText, formatWhen } from './utils.js';

let chats = [];
let currentChat = null;
let polling = null;
const POLL_MS = 5000;

document.addEventListener('DOMContentLoaded', init);

function init() {
  document.getElementById('btnRefresh').addEventListener('click', loadChats);
  document.getElementById('btnSend').addEventListener('click', onSend);
  document.getElementById('inputMessage').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); onSend(); }
  });

  loadChats();
}

async function loadChats(page=1) {
  setDisabled(true);
  const list = document.getElementById('contactsList');
  list.innerHTML = '<div class="text-gray-500 italic p-2">Carregando conversas...</div>';
  try {
    const json = await window.TimelinesAPI.listChats(page);
    const apiChats = json?.data?.chats || [];
    chats = apiChats;
    renderChatList(apiChats);
  } catch (e) {
    list.innerHTML = `<div class="text-red-600 p-2">Falha ao carregar: ${safeText(e.message)}</div>`;
  } finally {
    setDisabled(false);
  }
}

function renderChatList(items) {
  const list = document.getElementById('contactsList');
  list.innerHTML = '';
  if (!items.length) {
    list.innerHTML = '<div class="text-gray-500 italic p-2">Nenhuma conversa encontrada.</div>';
    return;
  }
  items.forEach(chat => {
    const row = el('div','p-2 rounded hover:bg-[#f0f9ff] flex items-center gap-3 contact-line');
    row.innerHTML = `
      <img src="${safeText(chat.photo) || 'img/logo.png'}" class="w-10 h-10 rounded-full" alt="">
      <div class="flex-1">
        <div class="font-semibold">${safeText(chat.name) || safeText(chat.phone)}</div>
        <div class="text-xs text-gray-500">${safeText(chat.phone)} • ${chat.is_group ? 'Grupo' : '1:1'}</div>
      </div>
      <div class="text-xs text-gray-400">${chat.last_message_timestamp ? safeText(new Date(chat.last_message_timestamp).toLocaleDateString()) : ''}</div>
    `;
    row.addEventListener('click', () => selectChat(chat));
    list.appendChild(row);
  });
}

function selectChat(chat) {
  currentChat = chat;
  // header
  document.getElementById('chatName').textContent = chat.name || chat.phone || `Chat ${chat.id}`;
  document.getElementById('chatAvatar').src = chat.photo || 'img/logo.png';
  document.getElementById('chatOrigin').textContent = chat.is_group ? 'Grupo WhatsApp' : 'Contato WhatsApp';
  const btnExt = document.getElementById('btnOpenExternal');
  btnExt.href = chat.chat_url || '#';

  // habilitar composer
  setDisabled(false, true);

  // limpar mensagens e carregar
  const area = document.getElementById('messagesArea');
  area.innerHTML = '<div class="text-gray-500 italic">Carregando mensagens...</div>';

  loadMessages(true);
  startPolling();
}

async function loadMessages(scrollBottom=false) {
  if (!currentChat) return;
  try {
    const json = await window.TimelinesAPI.getChatMessages(currentChat.id, { limit: 100 });
    const msgs = (json?.data?.messages || []).sort((a,b) => {
      const ta = +new Date(a.timestamp || a.created_timestamp || 0);
      const tb = +new Date(b.timestamp || b.created_timestamp || 0);
      return ta - tb;
    });
    renderMessages(msgs);
    if (scrollBottom) scrollToBottom();
  } catch (e) {
    console.error('Erro carregando mensagens:', e);
  }
}

function renderMessages(messages) {
  const area = document.getElementById('messagesArea');
  area.innerHTML = '';
  if (!messages.length) {
    area.innerHTML = '<div class="text-gray-500 italic">Sem mensagens ainda.</div>';
    return;
  }
  messages.forEach(m => {
    // Timelines: campos comuns -> m.text || m.body; m.from_me || m.fromMe
    const fromMe = m.from_me ?? m.fromMe ?? false;
    const text = safeText(m.text || m.body);
    const when = formatWhen(m.timestamp || m.created_timestamp || m.created_at);

    const wrap = el('div','flex flex-col');
    const bubble = el('div', `bubble ${fromMe ? 'sent':'recv'}`);
    bubble.textContent = text;

    const small = el('div','time-small ml-1', when);
    wrap.appendChild(bubble);
    wrap.appendChild(small);
    area.appendChild(wrap);
  });
}

function scrollToBottom() {
  const area = document.getElementById('messagesArea');
  area.scrollTop = area.scrollHeight;
}

async function onSend() {
  if (!currentChat) return;
  const input = document.getElementById('inputMessage');
  const txt = input.value.trim();
  if (!txt) return;

  // otimismo visual
  appendLocal({ text: txt, from_me: true, timestamp: new Date().toISOString() });
  input.value = '';

  try {
    await window.TimelinesAPI.sendChatMessage(currentChat.id, txt);
    // recarregar para pegar IDs e status
    setTimeout(() => loadMessages(true), 300);
  } catch (e) {
    alert('Falha ao enviar mensagem. Verifique o backend/proxy e a API Key.');
    console.error(e);
  }
}

function appendLocal(m) {
  const area = document.getElementById('messagesArea');
  if (!area.querySelector('.bubble')) area.innerHTML = '';
  const fromMe = m.from_me ?? m.fromMe ?? false;
  const wrap = el('div','flex flex-col');
  const bubble = el('div', `bubble ${fromMe ? 'sent':'recv'}`);
  bubble.textContent = m.text || m.body || '';
  const small = el('div','time-small ml-1', formatWhen(m.timestamp || m.created_timestamp || m.created_at || Date.now()));
  wrap.appendChild(bubble);
  wrap.appendChild(small);
  area.appendChild(wrap);
  scrollToBottom();
}

function setDisabled(disabled, composerOnly=false) {
  const input = document.getElementById('inputMessage');
  const send = document.getElementById('btnSend');
  if (!composerOnly) {
    // poderia desabilitar lista, etc.
  }
  input.disabled = disabled ? true : false;
  send.disabled = disabled ? true : false;
}

function startPolling() {
  stopPolling();
  polling = setInterval(() => loadMessages(), POLL_MS);
}
function stopPolling() {
  if (polling) clearInterval(polling);
  polling = null;
}
