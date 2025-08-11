/* ==== Gerenciamento localStorage - Contatos, Mensagens e Conversas ==== */

const storageKeys = {
  contatos: "contatos",
  mensagens: "mensagens",
  conversasInfo: "conversasInfo",
  usuarioLogado: "usuarioLogado",
};

// --------------------- CONTATOS ------------------------

/**
 * Retorna a lista de contatos do localStorage (array de objetos)
 */
function getContatos() {
  return JSON.parse(localStorage.getItem(storageKeys.contatos)) || [];
}

/**
 * Salva lista de contatos no localStorage
 * @param {Array} contatos 
 */
function setContatos(contatos) {
  localStorage.setItem(storageKeys.contatos, JSON.stringify(contatos));
}

/**
 * Adiciona um novo contato (objeto com nome, numero, origem, endereco)
 * @param {Object} contato 
 */
function addContato(contato) {
  if (!contato.nome || !contato.numero || !contato.origem) {
    throw new Error("Contato deve ter nome, número e origem obrigatórios");
  }
  const contatos = getContatos();
  // Evita duplicados pelo número
  if (contatos.some(c => c.numero === contato.numero)) {
    throw new Error("Contato já existe com esse número");
  }
  contatos.push(contato);
  setContatos(contatos);
}

/**
 * Remove contato e suas mensagens e conversaInfo pelo número
 * @param {string} numero 
 */
function removeContato(numero) {
  let contatos = getContatos();
  contatos = contatos.filter(c => c.numero !== numero);
  setContatos(contatos);

  // Remove mensagens e conversasInfo
  const mensagens = getMensagens();
  const conversasInfo = getConversasInfo();
  delete mensagens[numero];
  delete conversasInfo[numero];
  setMensagens(mensagens);
  setConversasInfo(conversasInfo);
}

// --------------------- MENSAGENS ------------------------

/**
 * Retorna objeto de mensagens: { numero: [ { texto, timestamp } ] }
 */
function getMensagens() {
  return JSON.parse(localStorage.getItem(storageKeys.mensagens)) || {};
}

/**
 * Salva objeto de mensagens no localStorage
 * @param {Object} mensagens 
 */
function setMensagens(mensagens) {
  localStorage.setItem(storageKeys.mensagens, JSON.stringify(mensagens));
}

/**
 * Adiciona mensagem para um número
 * @param {string} numero 
 * @param {string} texto 
 * @param {number} timestamp 
 */
function addMensagem(numero, texto, timestamp = Date.now()) {
  if (!numero || !texto) throw new Error("Número e texto da mensagem são obrigatórios");
  const mensagens = getMensagens();
  if (!mensagens[numero]) mensagens[numero] = [];
  mensagens[numero].push({ texto, timestamp });
  setMensagens(mensagens);
}

// --------------------- CONVERSAS (duração) ------------------------

/**
 * Retorna objeto conversasInfo: { numero: { duracaoTotalSeg, sessoes: [ { inicioTimestamp, fimTimestamp } ] } }
 */
function getConversasInfo() {
  return JSON.parse(localStorage.getItem(storageKeys.conversasInfo)) || {};
}

/**
 * Salva objeto conversasInfo no localStorage
 * @param {Object} info 
 */
function setConversasInfo(info) {
  localStorage.setItem(storageKeys.conversasInfo, JSON.stringify(info));
}

/**
 * Adiciona uma sessão de conversa para um contato
 * @param {string} numero 
 * @param {number} inicioTimestamp 
 * @param {number} fimTimestamp 
 */
function addSessaoConversa(numero, inicioTimestamp, fimTimestamp) {
  if (!numero || !inicioTimestamp || !fimTimestamp) throw new Error("Dados incompletos da sessão");

  const info = getConversasInfo();
  if (!info[numero]) {
    info[numero] = {
      duracaoTotalSeg: 0,
      sessoes: [],
    };
  }
  const duracaoSeg = Math.floor((fimTimestamp - inicioTimestamp) / 1000);
  info[numero].duracaoTotalSeg += duracaoSeg;
  info[numero].sessoes.push({ inicioTimestamp, fimTimestamp });
  setConversasInfo(info);
}

// --------------------- USUÁRIO LOGADO ------------------------

/**
 * Retorna usuário logado (string ou null)
 */
function getUsuarioLogado() {
  return localStorage.getItem(storageKeys.usuarioLogado) || null;
}

/**
 * Define usuário logado
 * @param {string} usuario 
 */
function setUsuarioLogado(usuario) {
  localStorage.setItem(storageKeys.usuarioLogado, usuario);
}

/**
 * Remove usuário logado (logout)
 */
function logout() {
  localStorage.removeItem(storageKeys.usuarioLogado);
  window.location.href = "intranet.html";
}

// --------------------- FILTROS ------------------------

const disparos = [
  "oi",
  "bom dia",
  "boa tarde",
  "boa noite",
  "olá",
  "ola",
];

/**
 * Verifica se a mensagem é considerada disparo (não conta como conversa)
 * @param {string} texto 
 * @returns {boolean}
 */
function isDisparo(texto) {
  if (!texto) return false;
  const txt = texto.toLowerCase().trim();
  return disparos.includes(txt);
}

// --------------------- MÉTRICAS PARA DASHBOARD ------------------------

/**
 * Calcula média semanal de mensagens válidas nos últimos 4 semanas
 * @param {Object} mensagens 
 * @returns {number} média
 */
function calcularMediaSemanal(mensagens) {
  const agora = Date.now();
  const quatroSemanasMs = 4 * 7 * 24 * 60 * 60 * 1000;
  const limite = agora - quatroSemanasMs;

  let totalMsgsUltimas4Semanas = 0;

  for (const msgs of Object.values(mensagens)) {
    for (const m of msgs) {
      if (m.timestamp >= limite && !isDisparo(m.texto)) {
        totalMsgsUltimas4Semanas++;
      }
    }
  }

  return (totalMsgsUltimas4Semanas / 4).toFixed(2);
}

/**
 * Formata segundos para string mm:ss
 * @param {number} segundos 
 * @returns {string}
 */
function formatarDuracao(segundos) {
  const m = Math.floor(segundos / 60);
  const s = segundos % 60;
  return `${m}m ${s}s`;
}

/**
 * Formata timestamp para string amigável "Hoje", "Ontem", "Há X dias"
 * @param {number|null} timestamp 
 * @returns {string}
 */
function tempoDesde(timestamp) {
  if (!timestamp) return "Nunca";
  const diffMs = Date.now() - timestamp;
  const diffDias = Math.floor(diffMs / (1000 * 60 * 60 * 24));
  if (diffDias === 0) return "Hoje";
  if (diffDias === 1) return "Ontem";
  return `Há ${diffDias} dias`;
}

// --------------------- EXCLUSÃO ------------------------

/**
 * Exclui contato, mensagens e conversaInfo
 * @param {string} numero 
 */
function excluirContatoCompleto(numero) {
  if (!confirm("Deseja excluir este contato e suas mensagens?")) return;
  removeContato(numero);
  alert("Contato e mensagens excluídos com sucesso.");
}

// --------------------- UTILITÁRIOS ------------------------

/**
 * Busca contato pelo número
 * @param {string} numero 
 * @returns {Object|null}
 */
function getContatoPorNumero(numero) {
  const contatos = getContatos();
  return contatos.find(c => c.numero === numero) || null;
}
