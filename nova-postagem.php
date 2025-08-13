<?php
include 'conexao.php';

$mensagemSucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $tipo = $_POST['tipo']; // 'rascunho' ou 'publicar'
    $status = $tipo === 'publicar' ? 'publicado' : 'rascunho';
    $autor = 'Gilvani'; // Autor fixo

    $stmt = $conn->prepare("INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $conteudo, $autor, $status);

    if ($stmt->execute()) {
        $mensagemSucesso = $status === 'publicado' ? "Post publicado com sucesso!" : "Rascunho salvo com sucesso!";
    } else {
        $mensagemSucesso = "Erro ao salvar postagem: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nova Postagem - Intranet do Gringo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
  <style>
    #editor { min-height: 250px; border:1px solid #ccc; padding:10px; border-radius:4px; background:white; overflow-y:auto;}
    #editor:focus { outline:2px solid #09679c; }
    .toolbar button { background:#09679c;color:white;border:none;padding:6px 10px;border-radius:4px;cursor:pointer;margin-right:4px; }
    .toolbar button:hover { background:#074d6b; }
    .toolbar select { padding:5px; border-radius:4px; border:1px solid #ccc; margin-right:8px; }
    .emoji-picker { max-height:120px; overflow-y:scroll; border:1px solid #ccc; background:white; border-radius:4px; padding:5px; display:flex; flex-wrap:wrap; gap:6px; margin-top:6px; }
    .emoji-picker span { cursor:pointer; font-size:20px; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<header class="flex justify-between items-center bg-[#09679c] text-white p-4 shadow-md">
  <div class="flex items-center gap-3">
    <img src="img/logo.png" alt="Logo Gringo" class="h-10 w-auto" />
    <h1 class="text-xl font-bold">Nova Postagem</h1>
  </div>
  <nav id="mainMenu" class="flex items-center gap-3"></nav>
</header>

<main class="flex-grow max-w-4xl mx-auto p-6">
  <form id="formPostagem" class="bg-white rounded shadow p-6 space-y-6">
    <div id="msgSucesso" class="text-center font-semibold mb-4"></div>
    
    <div>
      <label for="titulo" class="block font-semibold mb-1 text-gray-700">Título</label>
      <input type="text" id="titulo" name="titulo"
        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#09679c]"
        placeholder="Digite o título da postagem" required />
    </div>

    <div>
      <label class="block font-semibold mb-1 text-gray-700">Conteúdo</label>
      <div class="toolbar mb-2 flex flex-wrap items-center gap-1">
        <button type="button" onclick="format('bold')"><i class="fas fa-bold"></i></button>
        <button type="button" onclick="format('italic')"><i class="fas fa-italic"></i></button>
        <button type="button" onclick="format('underline')"><i class="fas fa-underline"></i></button>
        <select onchange="format('fontName', this.value)">
          <option value="Arial">Arial</option>
          <option value="Courier New">Courier New</option>
          <option value="Georgia">Georgia</option>
          <option value="Tahoma">Tahoma</option>
          <option value="Times New Roman">Times New Roman</option>
          <option value="Verdana">Verdana</option>
        </select>
        <select onchange="format('fontSize', this.value)">
          <option value="1">10px</option>
          <option value="2">13px</option>
          <option value="3" selected>16px</option>
          <option value="4">18px</option>
          <option value="5">24px</option>
          <option value="6">32px</option>
          <option value="7">48px</option>
        </select>
        <button type="button" onclick="format('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
        <button type="button" onclick="format('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
        <button type="button" onclick="inserirLink()"><i class="fas fa-link"></i></button>
        <input type="file" id="inputImagem" accept="image/*" style="display:none" onchange="inserirImagem(event)" />
        <button type="button" onclick="document.getElementById('inputImagem').click()"><i class="fas fa-image"></i></button>
        <button type="button" onclick="toggleEmojiPicker()"><i class="fas fa-smile"></i></button>
      </div>
      <div id="emojiPicker" class="emoji-picker hidden"></div>
      <div id="editor" contenteditable="true" spellcheck="true" class="prose max-w-none"></div>
      <input type="hidden" id="conteudo" name="conteudo">
    </div>

    <div class="flex gap-3">
      <button type="submit" name="tipo" value="rascunho" class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-6 py-2 rounded">Salvar Rascunho</button>
      <button type="submit" name="tipo" value="publicar" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded">Publicar</button>
    </div>
  </form>
</main>

<script>
const usuariosAutorizados = ["gabriel.amaral","graziele.albuquerque","karina.maia","cristiano.santos","gilvaniogringo"];
const usuarioLogado = localStorage.getItem("usuarioLogado");

function montarMenu() {
  const menu = document.getElementById("mainMenu");
  menu.innerHTML = `
    <a href="painel.html" class="bg-[#09679c] border border-white px-3 py-2 rounded flex items-center gap-1">Painel</a>
    <a href="nova-postagem.html" class="bg-[#053646] border border-white px-3 py-2 rounded flex items-center gap-1">Nova Postagem</a>
    <a href="publicacoes.html" class="bg-[#09679c] border border-white px-3 py-2 rounded flex items-center gap-1">Últimas Publicações</a>
    <a href="rascunhos.html" class="bg-[#09679c] border border-white px-3 py-2 rounded flex items-center gap-1">Rascunhos</a>
    <a href="perfil.html" class="bg-[#09679c] border border-white px-3 py-2 rounded flex items-center gap-1">Perfil</a>
    <button onclick="logout()" class="bg-[#053646] px-3 py-2 rounded flex items-center gap-1">Sair</button>
  `;
}

function logout() {
  localStorage.removeItem("usuarioLogado");
  window.location.href = "intranet.html";
}

function format(cmd, val = null) { document.execCommand(cmd, false, val); document.getElementById("editor").focus(); }
function inserirLink() { const url = prompt("Digite o URL do link:"); if(url) format("createLink", url); }
function toggleEmojiPicker() { document.getElementById("emojiPicker").classList.toggle("hidden"); }
function inserirEmoji(emoji) { format("insertText", emoji); toggleEmojiPicker(); }
function popularEmojis() {
  const picker = document.getElementById("emojiPicker");
  const emojis = ["😀","😃","😄","😁","😆","😅","😂","🤣","😊","😍","😎","😢","😭","😡","👍","👎","🙏","🎉","🔥","💡"];
  emojis.forEach(e => { const span = document.createElement("span"); span.textContent = e; span.onclick = () => inserirEmoji(e); picker.appendChild(span); });
}
function inserirImagem(event) {
  const file = event.target.files[0]; if (!file) return;
  const reader = new FileReader();
  reader.onload = e => { const img = document.createElement("img"); img.src = e.target.result; img.style.maxWidth="100%"; document.getElementById("editor").appendChild(img); };
  reader.readAsDataURL(file);
  event.target.value="";
}
function validarForm() {
  if (!usuarioLogado || !usuariosAutorizados.includes(usuarioLogado)) { alert("Você não tem permissão para publicar."); return false; }
  const conteudoHTML = document.getElementById("editor").innerHTML.trim();
  if (!conteudoHTML) { alert("O conteúdo não pode estar vazio."); return false; }
  document.getElementById("conteudo").value = conteudoHTML;
  return true;
}

// AJAX para salvar postagem
const form = document.getElementById("formPostagem");
form.addEventListener("submit", async e => {
  e.preventDefault();
  if (!validarForm()) return;
  const btnClicado = document.activeElement.value;
  const formData = new FormData();
  formData.append("titulo", document.getElementById("titulo").value.trim());
  formData.append("conteudo", document.getElementById("editor").innerHTML.trim());
  formData.append("tipo", btnClicado);

  try {
    const resp = await fetch("salvar-postagem.php",{ method:"POST", body:formData });
    const data = await resp.json();
    const msgEl = document.getElementById("msgSucesso");
    if(data.sucesso){
      msgEl.textContent = data.mensagem;
      msgEl.classList.remove("text-red-600"); msgEl.classList.add("text-green-600");
      if(data.tipo==="publicar") { document.getElementById("titulo").value=""; document.getElementById("editor").innerHTML=""; }
    } else { msgEl.textContent=data.mensagem||"Erro ao salvar."; msgEl.classList.remove("text-green-600"); msgEl.classList.add("text-red-600"); }
  } catch(err){ console.error(err); alert("Erro ao conectar com o servidor."); }
});

window.onload = () => {
  if (!usuarioLogado || !usuariosAutorizados.includes(usuarioLogado)) { alert("Acesso negado."); window.location.href="intranet.html"; return; }
  montarMenu();
  popularEmojis();
};
</script>
</body>
</html>
