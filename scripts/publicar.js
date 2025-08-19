// scripts/publicar.js

// Função para salvar rascunho (tipo "rascunho")
function salvarRascunho() {
  publicar("nova-postagem.php", "rascunho");
}

// Função principal para publicar ou salvar rascunho
function publicar(url = "nova-postagem.php", tipo = "publicar") {
  const titulo = document.getElementById("titulo").value.trim();
  const conteudo = document.getElementById("editor").innerHTML.trim();

  // Validação básica
  if (!titulo || !conteudo || conteudo === "<br>" || conteudo === "") {
    alert("Por favor, preencha o título e escreva algo no conteúdo.");
    return;
  }

  // Prepara os dados para envio
  const formData = new FormData();
  formData.append("titulo", titulo);
  formData.append("conteudo", conteudo);
  formData.append("tipo", tipo);

  // Envia via fetch para o PHP
  fetch(url, {
    method: "POST",
    body: formData
  })
    .then(resp => resp.json())
    .then(data => {
      const msgEl = document.getElementById("mensagem");
      if (data.sucesso) {
        msgEl.textContent = data.mensagem;
        msgEl.classList.remove("hidden");
        msgEl.classList.remove("text-red-600");
        msgEl.classList.add("text-green-600");

        // Se foi publicado, limpa formulário
        if (tipo === "publicar") {
          document.getElementById("form-postagem").reset();
          document.getElementById("editor").innerHTML = "";
        }
      } else {
        msgEl.textContent = data.mensagem || "Erro ao salvar a postagem.";
        msgEl.classList.remove("text-green-600");
        msgEl.classList.add("text-red-600");
        msgEl.classList.remove("hidden");
      }
    })
    .catch(err => {
      console.error(err);
      alert("Erro ao conectar com o servidor. Verifique se o PHP está acessível.");
    });
}

// Inicializa botão e permissões (opcional)
document.addEventListener("DOMContentLoaded", () => {
  const usuarioLogado = localStorage.getItem("usuarioLogado");
  const usuariosAutorizados = ["gabriel.amaral","graziele.albuquerque","karina.maia","cristiano.santos","gilvaniogringo"];
  
  if (!usuarioLogado || !usuariosAutorizados.includes(usuarioLogado)) {
    alert("Acesso negado.");
    window.location.href = "intranet.html";
  }
});
