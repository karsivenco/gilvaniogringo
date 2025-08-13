<script src="localStorage.js"></script>
<script>
const usuarioLogado = localStorage.getItem("usuarioLogado");

// Gera ID único
function gerarId() {
  return Date.now().toString();
}

// Função chamada ao enviar o formulário
function enviarPostagem(event) {
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
    autor: usuarioLogado
  };

  if (tipoBotao === "rascunho") {
    salvarPostagem(postagem, "rascunhos");
    alert("Rascunho salvo com sucesso!");
    document.getElementById("formPostagem").reset();
    document.getElementById("editor").innerHTML = "";
  } else if (tipoBotao === "publicar") {
    salvarPostagem(postagem, "enviar");
    alert("Publicação realizada com sucesso!");
    document.getElementById("formPostagem").reset();
    document.getElementById("editor").innerHTML = "";
  }
}

// Adiciona listener no form
document.getElementById("formPostagem").addEventListener("submit", enviarPostagem);
</script>
