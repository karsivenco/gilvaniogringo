<script>
// ----------------- Usuário Logado -----------------
const usuarioLogado = localStorage.getItem("usuarioLogado");

// ----------------- Gera ID único -----------------
function gerarId() {
  return Date.now().toString();
}

// ----------------- Funções de Cliente -----------------

// Salvar cliente no localStorage
function salvarCliente(cliente) {
  let clientes = JSON.parse(localStorage.getItem("clientes")) || [];
  // Se já existe (mesmo nome e número), atualiza
  const index = clientes.findIndex(c => c.numero === cliente.numero);
  if (index !== -1) {
    clientes[index] = cliente;
  } else {
    clientes.push(cliente);
  }
  localStorage.setItem("clientes", JSON.stringify(clientes));
}

// Obter todos os clientes
function obterClientes() {
  return JSON.parse(localStorage.getItem("clientes")) || [];
}

// ----------------- Funções de Postagem -----------------

// Salvar postagem no localStorage
function salvarPostagem(postagem, tipo) {
  let chave = tipo === "rascunhos" ? "rascunhos" : "publicacoes";
  let lista = JSON.parse(localStorage.getItem(chave)) || [];
  lista.push(postagem);
  localStorage.setItem(chave, JSON.stringify(lista));
}

// Verifica se usuário pode publicar
function podeUsuarioPublicar(usuario) {
  const permissoes = JSON.parse(localStorage.getItem("usuarios")) || [];
  const u = permissoes.find(u => u.usuario === usuario);
  return u ? u.publicar : false;
}

// ----------------- Formulário de Postagem -----------------
const formPostagem = document.getElementById("formPostagem");
if (formPostagem) {
  formPostagem.addEventListener("submit", function(event) {
    event.preventDefault();

    if (!usuarioLogado || !podeUsuarioPublicar(usuarioLogado)) {
      alert("Você não tem permissão para publicar.");
      return;
    }

    const titulo = document.getElementById("titulo").value.trim();
    const conteudo = document.getElementById("editor").innerHTML.trim();

    if (!titulo || !conteudo) {
      alert("Título e conteúdo não podem estar vazios.");
      return;
    }

    const tipoBotao = event.submitter.value; // "rascunho" ou "publicar"

    const postagem = {
      id: gerarId(),
      titulo: titulo,
      conteudo: conteudo,
      autor: usuarioLogado,
      data: new Date().toISOString()
    };

    salvarPostagem(postagem, tipoBotao);
    alert(tipoBotao === "rascunho" ? "Rascunho salvo com sucesso!" : "Publicação realizada com sucesso!");
    formPostagem.reset();
    document.getElementById("editor").innerHTML = "";
  });
}

// ----------------- Formulário de Cadastro -----------------
const formCadastro = document.getElementById("formCadastro");
if (formCadastro) {
  formCadastro.addEventListener("submit", function(event) {
    event.preventDefault();

    const cliente = {
      id: gerarId(),
      nome: document.getElementById("nome").value.trim(),
      numero: document.getElementById("numero").value.trim(),
      endereco: document.getElementById("endereco").value.trim(),
      origem: document.getElementById("origem").value,
      municipio: document.getElementById("municipio").value,
      bairro: document.getElementById("bairro").value
    };

    if (!cliente.nome || !cliente.numero || !cliente.municipio) {
      alert("Nome, número e município são obrigatórios!");
      return;
    }

    salvarCliente(cliente);

    alert("Cliente cadastrado com sucesso!");
    formCadastro.reset();
    window.location.href = "contatos.html"; // redireciona para contatos
  });
}
</script>
