/* ==== Gerenciamento localStorage - Contatos, Mensagens, Conversas, Usuário e Logs ==== */

const storageKeys = {
  contatos: "contatos",
  mensagens: "mensagens",
  conversasInfo: "conversasInfo",
  usuarioLogado: "usuarioLogado",
  usuariosLogados: "usuariosLogados", // lista dos usuários logados (array)
  logAtividadesPrefix: "logAtividades_", // chave prefixo para logs do usuário
};

// ----------- CONTATOS -------------------

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

  // Remove mensagens e conversasInfo relacionadas
  const mensagens = getMensagens();
  const conversasInfo = getConversasInfo();
  delete mensagens[numero];
  delete conversasInfo[numero];
  setMensagens(mensagens);
  setConversasInfo(conversasInfo);
}

// ----------- MENSAGENS -------------------

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

// ----------- CONVERSAS (duração) -------------------

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
    info[numero] = { duracaoTotalSeg: 0, sessoes: [] };
  }
  const duracaoSeg = Math.floor((fimTimestamp - inicioTimestamp) / 1000);
  info[numero].duracaoTotalSeg += duracaoSeg;
  info[numero].sessoes.push({ inicioTimestamp, fimTimestamp });
  setConversasInfo(info);
}

// ----------- USUÁRIO LOGADO -------------------

function getUsuarioLogado() {
  return localStorage.getItem(storageKeys.usuarioLogado) || null;
}

function setUsuarioLogado(usuario) {
  localStorage.setItem(storageKeys.usuarioLogado, usuario);
  registrarUsuarioLogado(usuario);
}

function logout() {
  const usuario = getUsuarioLogado();
  if (usuario) {
    removerUsuarioLogado(usuario);
  }
  localStorage.removeItem(storageKeys.usuarioLogado);
  window.location.href = "intranet.html";
}

// ----------- USUÁRIOS LOGADOS (lista) -------------------

function carregarUsuariosLogados() {
  const logados = localStorage.getItem(storageKeys.usuariosLogados);
  return logados ? JSON.parse(logados) : [];
}

function salvarUsuariosLogados(usuarios) {
  localStorage.setItem(storageKeys.usuariosLogados, JSON.stringify(usuarios));
}

function registrarUsuarioLogado(usuario) {
  if (!usuario) return;
  let usuarios = carregarUsuariosLogados();
  if (!usuarios.includes(usuario)) {
    usuarios.push(usuario);
    salvarUsuariosLogados(usuarios);
  }
}

function removerUsuarioLogado(usuario) {
  if (!usuario) return;
  let usuarios = carregarUsuariosLogados();
  usuarios = usuarios.filter(u => u !== usuario);
  salvarUsuariosLogados(usuarios);
}

// ----------- LOG DE ATIVIDADES POR USUÁRIO -------------------

function getChaveLog(usuario) {
  return storageKeys.logAtividadesPrefix + usuario;
}

function getLogAtividades(usuario) {
  const logJSON = localStorage.getItem(getChaveLog(usuario));
  return logJSON ? JSON.parse(logJSON) : [];
}

function setLogAtividades(usuario, log) {
  localStorage.setItem(getChaveLog(usuario), JSON.stringify(log));
}

function addAtividadeLog(usuario, acao) {
  if (!usuario || !acao) return;
  const log = getLogAtividades(usuario);
  log.unshift({ acao, timestamp: Date.now() });
  setLogAtividades(usuario, log);
}

// ----------- AUTENTICAÇÃO (USUÁRIOS E SENHAS) -------------------

// Essa função deve ser usada na página de login (intranet.html) para validar usuário/senha,
// utilizando o objeto 'usuariosSenhas' que deve ser importado do arquivo senhas.js externo (por segurança)
function validarLogin(usuario, senha, usuariosSenhas) {
  if (!usuario || !senha) return false;
  return usuariosSenhas[usuario] && usuariosSenhas[usuario] === senha;
}

// ----------- PERMISSÕES -------------------

const permissoesUsuarios = {
  // Usuários que podem postar publicações
  podePublicar: [
    "gabriel.amaral",
    "graziele.albuquerque",
    "karina.maia",
    "cristiano.santos",
    "gilvaniogringo",
  ],
  // Pode acessar dashboard gerencial
  acessoDashboardGerente: [
    "gilvaniogringo",
  ],
  // Outros tipos de permissão podem ser adicionados aqui
};

function podeUsuarioPublicar(usuario) {
  return permissoesUsuarios.podePublicar.includes(usuario);
}

function podeAcessarDashboard(usuario) {
  return permissoesUsuarios.acessoDashboardGerente.includes(usuario);
}

// ----------- UTILS -------------------

function getContatoPorNumero(numero) {
  const contatos = getContatos();
  return contatos.find(c => c.numero === numero) || null;
}

const disparos = ["oi", "bom dia", "boa tarde", "boa noite", "olá", "ola"];
function isDisparo(texto) {
  if (!texto) return false;
  return disparos.includes(texto.toLowerCase().trim());
}

function calcularMediaSemanal(mensagens) {
  const agora = Date.now();
  const quatroSemanasMs = 4 * 7 * 24 * 60 * 60 * 1000;
  const limite = agora - quatroSemanasMs;

  let totalMsgs = 0;

  for (const msgs of Object.values(mensagens)) {
    for (const m of msgs) {
      if (m.timestamp >= limite && !isDisparo(m.texto)) {
        totalMsgs++;
      }
    }
  }

  return (totalMsgs / 4).toFixed(2);
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

function excluirContatoCompleto(numero) {
  if (!confirm("Deseja excluir este contato e suas mensagens?")) return;
  removeContato(numero);
  alert("Contato e mensagens excluídos com sucesso.");
}
