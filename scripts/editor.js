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
  fetch("upload-image.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(url => {
      insertHTMLAtCursor(`<img src="${url}" alt="Imagem" style="max-width:100%;">`);
    })
    .catch(() => alert("Erro ao enviar imagem."));
}

function initToolbar() {
  const toolbar = document.getElementById("toolbar");
  if (!toolbar) return;
  toolbar.innerHTML = `
    <button type="button" data-cmd="bold"><b>B</b></button>
    <button type="button" data-cmd="insertUnorderedList">• Lista</button>
    <button type="button" id="emojiBtn">😊</button>
    <label class="cursor-pointer">
      📷 <input type="file" id="imgInput" class="hidden" accept="image/*">
    </label>
    <button type="button" onclick="customLinkPrompt()">Link</button>
    <button type="button" onclick="insertParagraph()">Parágrafo</button>
  `;

  toolbar.addEventListener("click", e => {
    const cmd = e.target.dataset.cmd;
    if (cmd) formatText(cmd);
  });
  document.getElementById("emojiBtn").addEventListener("click", toggleEmojiPanel);
  document.getElementById("imgInput").addEventListener("change", function() {
    uploadImagem(this);
  });
}

document.addEventListener("DOMContentLoaded", initToolbar);

