// localStorage.js

const storageKeys = {
  rascunhos: "rascunhos",
  publicacoes: "publicacoes",
};

// ======== GESTÃO DE POSTAGENS E RASCUNHOS ===========

// Retorna lista completa de rascunhos (array de objetos)
function getRascunhos() {
  return JSON.parse(localStorage.getItem(storageKeys.rascunhos)) || [];
}

// Salva lista completa de rascunhos
function setRascunhos(rascunhos) {
  localStorage.setItem(storageKeys.rascunhos, JSON.stringify(rascunhos));
}

// Retorna lista completa de publicações (array de objetos)
function getPublicacoes() {
  return JSON.parse(localStorage.getItem(storageKeys.publicacoes)) || [];
}

// Salva lista completa de publicações
function setPublicacoes(publicacoes) {
  localStorage.setItem(storageKeys.publicacoes, JSON.stringify(publicacoes));
}

// Salva ou atualiza um rascunho
function salvarOuAtualizarRascunho(postagem) {
  if (!postagem || !postagem.id) throw new Error("Postagem inválida para salvar rascunho");
  let rascunhos = getRascunhos();
  const idx = rascunhos.findIndex(p => p.id === postagem.id);
  if (idx >= 0) {
    rascunhos[idx] = postagem; // atualiza
  } else {
    rascunhos.push(postagem); // adiciona novo
  }
  setRascunhos(rascunhos);
}

// Salva ou atualiza uma publicação
function salvarOuAtualizarPublicacao(postagem) {
  if (!postagem || !postagem.id) throw new Error("Postagem inválida para salvar publicação");
  let publicacoes = getPublicacoes();
  const idx = publicacoes.findIndex(p => p.id === postagem.id);
  if (idx >= 0) {
    publicacoes[idx] = postagem; // atualiza
  } else {
    publicacoes.push(postagem); // adiciona novo
  }
  setPublicacoes(publicacoes);
}

// Remove postagem de rascunho pelo ID
function removerRascunho(id) {
  if (!id) return;
  let rascunhos = getRascunhos();
  rascunhos = rascunhos.filter(p => p.id !== id);
  setRascunhos(rascunhos);
}

// Remove publicação pelo ID
function removerPublicacao(id) {
  if (!id) return;
  let publicacoes = getPublicacoes();
  publicacoes = publicacoes.filter(p => p.id !== id);
  setPublicacoes(publicacoes);
}

// Busca um rascunho por ID
function buscarRascunhoPorId(id) {
  if (!id) return null;
  const rascunhos = getRascunhos();
  return rascunhos.find(p => p.id === id) || null;
}

// Busca uma publicação por ID
function buscarPublicacaoPorId(id) {
  if (!id) return null;
  const publicacoes = getPublicacoes();
  return publicacoes.find(p => p.id === id) || null;
}

// Retorna publicações do usuário logado (array)
function getPublicacoesPorAutor(autor) {
  if (!autor) return [];
  const publicacoes = getPublicacoes();
  return publicacoes.filter(p => p.autor === autor);
}

// Retorna rascunhos do usuário logado (array)
function getRascunhosPorAutor(autor) {
  if (!autor) return [];
  const rascunhos = getRascunhos();
  return rascunhos.filter(p => p.autor === autor);
}

/**
 * Salva uma postagem como rascunho ou publicada, atribuindo autor, id e data
 * @param {Object} postagem - objeto com ao menos id, titulo, conteudo
 * @param {string} status - "rascunho" ou "enviar"
 * @param {string} autor - login do usuário que está salvando
 */
function salvarPostagem(postagem, status, autor) {
  if (!postagem) throw new Error("Postagem inválida");
  if (!autor) throw new Error("Autor não informado");

  postagem.status = status;
  postagem.data = new Date().toISOString();
  postagem.autor = autor;

  if (status === "rascunho") {
    salvarOuAtualizarRascunho(postagem);
    // Remover da publicações caso exista
    removerPublicacao(postagem.id);
  } else if (status === "enviar") {
    salvarOuAtualizarPublicacao(postagem);
    // Remover da lista de rascunhos se existir
    removerRascunho(postagem.id);
  }
}

// Função para verificar se o usuário tem permissão para publicar
function podeUsuarioPublicar(usuario) {
  const autorizados = [
    "gabriel.amaral",
    "graziele.albuquerque",
    "karina.maia",
    "cristiano.santos",
    "gilvaniogringo",
  ];
  return autorizados.includes(usuario);
}
