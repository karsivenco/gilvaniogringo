<script>
// ----------------- Usuário Logado -----------------
const usuarioLogado = localStorage.getItem("usuarioLogado");

// ----------------- Gera ID único -----------------
function gerarId() {
  return Date.now().toString();
}

// ----------------- Funções de Cliente -----------------

// Salvar cliente no localStorage (adiciona ou atualiza pelo ID)
function salvarCliente(cliente) {
  let clientes = JSON.parse(localStorage.getItem("clientes")) || [];
  const index = clientes.findIndex(c => c.id === cliente.id);
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

// Obter cliente por ID
function obterCliente(id) {
  const clientes = obterClientes();
  return clientes.find(c => c.id === id);
}

// Excluir cliente
function excluirCliente(id) {
  let clientes = obterClientes();
  clientes = clientes.filter(c => c.id !== id);
  localStorage.setItem("clientes", JSON.stringify(clientes));
}

// ----------------- Funções de Postagem -----------------

function salvarPostagem(postagem, tipo) {
  let chave = tipo === "rascunhos" ? "rascunhos" : "publicacoes";
  let lista = JSON.parse(localStorage.getItem(chave)) || [];
  lista.push(postagem);
  localStorage.setItem(chave, JSON.stringify(lista));
}

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
      bairro: document.getElementById("bairro").value,
      mesAniversario: document.getElementById("mesAniversario")?.value || ""
    };

    if (!cliente.nome || !cliente.numero || !cliente.municipio) {
      alert("Nome, número e município são obrigatórios!");
      return;
    }

    salvarCliente(cliente);

    alert("Cliente cadastrado com sucesso!");
    formCadastro.reset();
    window.location.href = "contatos.html"; // redireciona
  });
}

// ----------------- Base.html: Carregar Ficha -----------------
function carregarFicha() {
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get("id");
  if (!id) return;

  const cliente = obterCliente(id);
  if (!cliente) return;

  document.getElementById("nome").value = cliente.nome;
  document.getElementById("numero").value = cliente.numero;
  document.getElementById("endereco").value = cliente.endereco;
  document.getElementById("origem").value = cliente.origem;
  document.getElementById("municipio").value = cliente.municipio;
  document.getElementById("bairro").value = cliente.bairro;
  if(document.getElementById("mesAniversario")) {
    document.getElementById("mesAniversario").value = cliente.mesAniversario;
  }

  // Salvar alterações
  const formFicha = document.getElementById("formFicha");
  if (formFicha) {
    formFicha.addEventListener("submit", e => {
      e.preventDefault();
      const atualizado = {
        id: cliente.id,
        nome: document.getElementById("nome").value.trim(),
        numero: document.getElementById("numero").value.trim(),
        endereco: document.getElementById("endereco").value.trim(),
        origem: document.getElementById("origem").value,
        municipio: document.getElementById("municipio").value,
        bairro: document.getElementById("bairro").value,
        mesAniversario: document.getElementById("mesAniversario")?.value || ""
      };
      salvarCliente(atualizado);
      alert("Ficha atualizada com sucesso!");
    });
  }

  // Botão de exclusão
  const btnExcluir = document.getElementById("btnExcluir");
  if (btnExcluir) {
    btnExcluir.addEventListener("click", () => {
      if (confirm(`Deseja realmente excluir ${cliente.nome}?`)) {
        excluirCliente(cliente.id);
        alert("Ficha excluída com sucesso!");
        window.location.href = "contatos.html";
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  carregarFicha();
});
</script>
