/* ==== Gerenciamento localStorage - Contatos, Mensagens, Conversas e Usuários Logados ==== */

const storageKeys = {
  contatos: "contatos",
  mensagens: "mensagens",
  conversasInfo: "conversasInfo",
  usuarioLogado: "usuarioLogado",
  usuariosLogadosAtivos: "usuariosLogadosAtivos" // nova chave para lista de usuários logados
};

// --- CONTATOS -- (mantém seu código original)

function getContatos() {
  return JSON.parse(localStorage.getItem(storageKeys.contatos)) || [];
}

function setContatos(contatos) {
  localStorage.setItem(storageKeys.contatos, JSON.stringify(contatos));
}

function addContato(contato) {
  if (!contato.nome || !contato.numero || !contato.origem) {
    throw new Error("Contato deve ter nome, número e origem obrigatórios");
  }
  const contatos = getContatos();
  if (contatos.some(c => c.numero === contato.numero)) {
    throw new Error("Contato já existe com esse número");
  }
  contatos.push(contato);
  setContatos(contatos);
}

function removeContato(numero) {
  let contatos = getContatos();
  contatos = contatos.filter(c => c.numero !== numero);
  setContatos(contatos);

  const mensagens = getMensagens();
  const conversasInfo = getConversasInfo();
  delete mensagens[numero];
  delete conversasInfo[numero];
  setMensagens(mensagens);
  setConversasInfo(conversasInfo);
}

// --- MENSAGENS -- (mantém seu código original)

function getMensagens() {
  return JSON.parse(localStorage.getItem(storageKeys.mensagens)) || {};
}

function setMensagens(mensagens) {
  localStorage.setItem(storageKeys.mensagens, JSON.stringify(mensagens));
}

function addMensagem(numero, texto, timestamp = Date.now()) {
  if (!numero || !texto) throw new Error("Número e texto da mensagem são obrigatórios");
  const mensagens = getMensagens();
  if (!mensagens[numero]) mensagens[numero] = [];
  mensagens[numero].push({ texto, timestamp });
  setMensagens(mensagens);
}

// --- CONVERSAS (duração) -- (mantém seu código original)

function getConversasInfo() {
  return JSON.parse(localStorage.getItem(storageKeys.conversasInfo)) || {};
}

function setConversasInfo(info) {
  localStorage.setItem(storageKeys.conversasInfo, JSON.stringify(info));
}

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

// --- USUÁRIO LOGADO -- (mantém seu código original)

function getUsuarioLogado() {
  return localStorage.getItem(storageKeys.usuarioLogado) || null;
}

function setUsuarioLogado(usuario) {
  localStorage.setItem(storageKeys.usuarioLogado, usuario);
}

function logout() {
  // Antes de remover usuarioLogado, remover da lista de usuarios logados ativos
  removerUsuarioLogado(getUsuarioLogado());
  localStorage.removeItem(storageKeys.usuarioLogado);
  window.location.href = "intranet.html";
}

// --- NOVO: GERENCIAMENTO DE USUÁRIOS LOGADOS ATIVOS ---

/**
 * Retorna array de usuários logados ativos (strings)
 * @returns {Array<string>}
 */
function getUsuariosLogadosAtivos() {
  return JSON.parse(localStorage.getItem(storageKeys.usuariosLogadosAtivos)) || [];
}

/**
 * Salva array de usuários logados ativos
 * @param {Array<string>} lista 
 */
function setUsuariosLogadosAtivos(lista) {
  localStorage.setItem(storageKeys.usuariosLogadosAtivos, JSON.stringify(lista));
}

/**
 * Adiciona usuário na lista de logados ativos, se ainda não existir
 * @param {string} usuario 
 */
function registrarUsuarioLogado(usuario) {
  if (!usuario) return;
  let lista = getUsuariosLogadosAtivos();
  if (!lista.includes(usuario)) {
    lista.push(usuario);
    setUsuariosLogadosAtivos(lista);
  }
}

/**
 * Remove usuário da lista de logados ativos
 * @param {string} usuario 
 */
function removerUsuarioLogado(usuario) {
  if (!usuario) return;
  let lista = getUsuariosLogadosAtivos();
  lista = lista.filter(u => u !== usuario);
  setUsuariosLogadosAtivos(lista);
}

// --- FILTROS -- (mantém seu código original)

const disparos = [
  "oi",
  "bom dia",
  "boa tarde",
  "boa noite",
  "olá",
  "ola",
];

function isDisparo(texto) {
  if (!texto) return false;
  const txt = texto.toLowerCase().trim();
  return disparos.includes(txt);
}

// --- MÉTRICAS PARA DASHBOARD -- (mantém seu código original)

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

function formatarDuracao(segundos) {
  const m = Math.floor(segundos / 60);
  const s = segundos % 60;
  return `${m}m ${s}s`;
}

function tempoDesde(timestamp) {
  if (!timestamp) return "Nunca";
  const diffMs = Date.now() - timestamp;
  const diffDias = Math.floor(diffMs / (1000 * 60 * 60 * 24));
  if (diffDias === 0) return "Hoje";
  if (diffDias === 1) return "Ontem";
  return `Há ${diffDias} dias`;
}

// --- EXCLUSÃO -- (mantém seu código original)

function excluirContatoCompleto(numero) {
  if (!confirm("Deseja excluir este contato e suas mensagens?")) return;
  removeContato(numero);
  alert("Contato e mensagens excluídos com sucesso.");
}

// --- UTILITÁRIOS -- (mantém seu código original)

function getContatoPorNumero(numero) {
  const contatos = getContatos();
  return contatos.find(c => c.numero === numero) || null;
}
