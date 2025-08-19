// publicar.js
function initPublicacao() {
  const form = document.getElementById("formPostagem");

  form.addEventListener("submit", async e => {
    e.preventDefault();

    const usuariosAutorizados = ["gabriel.amaral","graziele.albuquerque","karina.maia","cristiano.santos","gilvaniogringo"];
    const usuarioLogado = localStorage.getItem("usuarioLogado");
    if(!validarForm(usuariosAutorizados, usuarioLogado)) return;

    const btnClicado = document.activeElement.value; // "rascunho" ou "publicar"
    const formData = new FormData();
    formData.append("titulo", document.getElementById("titulo").value.trim());
    formData.append("conteudo", document.getElementById("editor").innerHTML.trim());
    formData.append("tipo", btnClicado);

    // Definir endpoint diferente se for rascunho ou publicar
    const url = btnClicado === "rascunho" ? "salvar-rascunho.php" : "salvar-postagem.php";

    try {
      const resp = await fetch(url, { method:"POST", body: formData });
      const data = await resp.json();
      const msgEl = document.getElementById("msgSucesso");

      if(data.sucesso) {
        msgEl.textContent = data.mensagem;
        msgEl.classList.remove("text-red-600"); msgEl.classList.add("text-green-600");

        // Limpa o editor só se for publicar
        if(btnClicado === "publicar") {
          document.getElementById("titulo").value = "";
          document.getElementById("editor").innerHTML = "";
        }
      } else {
        msgEl.textContent = data.mensagem || "Erro ao salvar.";
        msgEl.classList.remove("text-green-600"); msgEl.classList.add("text-red-600");
      }
    } catch(err) {
      console.error(err);
      alert("Erro ao conectar com o servidor.");
    }
  });
}
