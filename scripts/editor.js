// scripts/editor.js

function getEditorSelection() {
  const editor = document.getElementById("editor");
  editor.focus();
  return window.getSelection();
}

function insertHTMLAtCursor(html) {
  const sel = getEditorSelection();
  if (!sel.rangeCount) return;

  const range = sel.getRangeAt(0);
  const el = document.createElement("div");
  el.innerHTML = html;
  const frag = document.createDocumentFragment();
  let node, lastNode;
  while ((node = el.firstChild)) {
    lastNode = frag.appendChild(node);
  }
  range.deleteContents();
  range.insertNode(frag);
  if (lastNode) {
    range.setStartAfter(lastNode);
    range.collapse(true);
    sel.removeAllRanges();
    sel.addRange(range);
  }
}

function formatText(command) {
  document.getElementById("editor").focus();
  document.execCommand(command, false, null);
}

function insertParagraph() {
  insertHTMLAtCursor("<p>• Novo parágrafo</p>");
}

function customLinkPrompt() {
  const url = prompt("Digite a URL (comece com https://):");
  if (!url) return;
  const texto = prompt("Digite o texto que será exibido no link:");
  if (!texto) return;
  insertHTMLAtCursor(`<a href="${url}" target="_blank" rel="noopener noreferrer">${texto}</a>`);
}

function changeFont(font) {
  if (font) document.execCommand("fontName", false, font);
}

function insertEmoji(emoji) {
  insertHTMLAtCursor(emoji);
  document.getElementById("emojiPanel").style.display = "none";
}

function toggleEmojiPanel() {
  const panel = document.getElementById("emojiPanel");
  const emojis = "😀 😃 😄 😁 😆 😅 🤣 😂 🙂 🙃 😉 😊 😇 🥰 😍 🤩 😘 😗 ☺️ 😚 😙 😋 😛 😜 🤪 😝 🤑 🤗 🤭 🤫 🤔 🤐 😐 😑 😶 😏 😒 🙄 😬 😌 😔 😪 🤤 😴 😷 🤒 🤕 🤢 🤮 🤧 🥵 🥶 🥴 😵 🤯 🤠 🥳 😎 🤓 🧐 😕 🙁 ☹️ 😮 😯 😲 😳 🥺 😦 😧 😨 😰 😥 😢 😭 😱 😖 😣 😞 😓 😩 😫 😤 😡 😠 🤬 😈 👿 💀 ☠️ 💩 🤡 👹 👺 👻 👽 👾 🤖";
  panel.innerHTML = emojis.split(" ").map(e => `<span onclick="insertEmoji('${e}')">${e}</span>`).join("");
  panel.style.display = panel.style.display === "block" ? "none" : "block";
}

function uploadImagem(input) {
  const file = input.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append("imagem", file);
  fetch("upload-imagem.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(url => {
      insertHTMLAtCursor(`<img src="${url}" alt="Imagem" style="max-width:100%;">`);
    })
    .catch(() => alert("Erro ao enviar imagem."));
}
