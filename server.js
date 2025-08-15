// server.js
// Backend proxy para TimelinesAI (não expõe a API key no front)
import express from 'express';
import fetch from 'node-fetch';
import path from 'path';
import cors from 'cors';
import dotenv from 'dotenv';

dotenv.config();
const app = express();
app.use(cors());
app.use(express.json());

// >>> CONFIG <<<
const TIMELINES_API_BASE = process.env.TIMELINES_API_BASE || 'https://app.timelines.ai/integrations/api';
const TIMELINES_API_KEY  = process.env.TIMELINES_API_KEY; // coloque no .env
const PORT = process.env.PORT || 3000;

// arquivos estáticos (frontend)
app.use(express.static(path.resolve('public')));

// helper para chamar a API Timelines com bearer
async function tlFetch(url, options = {}) {
  const headers = {
    'Authorization': `Bearer ${TIMELINES_API_KEY}`,
    'Content-Type': 'application/json',
    ...(options.headers || {})
  };
  const res = await fetch(url, { ...options, headers });
  if (!res.ok) {
    const text = await res.text();
    throw new Error(`TimelinesAPI ${res.status}: ${text}`);
  }
  return res.json();
}

/** ========== ROTAS DE CONVERSA ========== **/

// Listar chats (paginação simples via query ?page=1)
app.get('/api/chats', async (req, res) => {
  try {
    const page = req.query.page ? `?page=${encodeURIComponent(req.query.page)}` : '';
    const data = await tlFetch(`${TIMELINES_API_BASE}/chats/${page}`);
    res.json(data);
  } catch (e) {
    res.status(500).json({ error: e.message });
  }
});

// Mensagens de um chat
app.get('/api/chats/:chatId/messages', async (req, res) => {
  try {
    const { chatId } = req.params;
    // suporte a desde/até/limit se desejar: ?since=timestamp
    const qs = new URLSearchParams(req.query).toString();
    const url = `${TIMELINES_API_BASE}/chats/${encodeURIComponent(chatId)}/messages/${qs ? `?${qs}` : ''}`;
    const data = await tlFetch(url);
    res.json(data);
  } catch (e) {
    res.status(500).json({ error: e.message });
  }
});

// Enviar mensagem num chat existente
app.post('/api/chats/:chatId/messages', async (req, res) => {
  try {
    const { chatId } = req.params;
    const body = req.body; // { text }
    const data = await tlFetch(`${TIMELINES_API_BASE}/chats/${encodeURIComponent(chatId)}/messages/`, {
      method: 'POST',
      body: JSON.stringify(body)
    });
    res.json(data);
  } catch (e) {
    res.status(500).json({ error: e.message });
  }
});

// Opcional: criar chat/abrir janela com número (se suportado pela sua conta)
// app.post('/api/chats', async (req, res) => { ... })

/** ========== WEBHOOK (opcional) ==========
 * Configure na Timelines um webhook apontando para /webhooks/timelines
 * Assim o front pode receber "push" sem esperar o polling
 */
app.post('/webhooks/timelines', async (req, res) => {
  // Armazene ou retransmita como preferir (ex: via WS/Socket.io).
  // Aqui só fazemos log e respondemos 200.
  console.log('Webhook Timelines:', JSON.stringify(req.body));
  res.sendStatus(200);
});

/** ========== SERVE APP ========== **/
app.get('/whatsapp', (_, res) => {
  res.sendFile(path.resolve('public/whatsapp.html'));
});

app.listen(PORT, () => {
  if (!TIMELINES_API_KEY) {
    console.warn('ATENÇÃO: TIMELINES_API_KEY não definida no .env');
  }
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});
