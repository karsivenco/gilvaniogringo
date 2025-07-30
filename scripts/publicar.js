// scripts/publicar.js

function salvarRascunho() {
  publicar("salvar-rascunho.php");
}

function publicar(url = "salvar.php") {
  const titulo = document.getElementById("titulo").value.trim();
  const link = document.getElementById("link").value.trim();
  const texto = document.getElementById("editor").innerHTML.trim();
  const agora = new Date();

  const dataISO = agora.toISOString();
  const data_formatada = `${agora.toLocaleDateString()} ${agora.toLocaleTimeString([], {
    hour: '2-digit',
    minute: '2-digit'
  })}`;

  // Validação
  if (!titulo || !texto || texto === "<br>" || texto === "") {
    alert("Por favor, preencha o título e escreva algo no texto.");
    return;
  }

  const formData = new FormData();
  formData.append("titulo", titulo);
  formData.append("link", link);
  formData.append("texto", texto);
  formData.append("data", dataISO);
  formData.append("data_formatada", data_formatada);

  fetch(url, {
    method: "POST",
    body: formData
  })
    .then(response => {
      if (!response.ok) throw new Error("Erro ao enviar os dados.");
      return response.text();
    })
    .then(res => {
      console.log("Retorno do servidor:", res);
      document.getElementById("mensagem").classList.remove("hidden");
      document.getElementById("form-postagem").reset();
      document.getElementById("editor").innerHTML = "";
      setTimeout(() => {
        window.location.href = "painel.html";
      }, 1500);
    })
    .catch(err => {
      console.error(err);
      alert("Erro ao salvar a postagem. Verifique se o arquivo PHP está correto e acessível.");
    });
}
