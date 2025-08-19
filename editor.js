// editor.js
function format(cmd, val=null){ document.execCommand(cmd,false,val); document.getElementById("editor").focus(); }
function inserirLink(){ const url=prompt("Digite a URL:"); if(url) format("createLink", url); }
function toggleEmojiPicker(){ document.getElementById("emojiPicker").classList.toggle("hidden"); }
function inserirEmoji(emoji){ format("insertText", emoji); toggleEmojiPicker(); }

function popularEmojis(){
  const picker = document.getElementById("emojiPicker");
  const emojis = ["😀","😃","😄","😁","😆","😅","😂","🤣","😊","😍","😎","😢","😭","😡","👍","👎","🙏","🎉","🔥","💡"];
  emojis.forEach(e => { const span=document.createElement("span"); span.textContent=e; span.onclick=()=>inserirEmoji(e); picker.appendChild(span); });
}

function inserirImagem(event){
  const file=event.target.files[0]; if(!file) return;
  const reader=new FileReader();
  reader.onload=e=>{ const img=document.createElement("img"); img.src=e.target.result; img.style.maxWidth="100%"; document.getElementById("editor").appendChild(img); };
  reader.readAsDataURL(file);
  event.target.value="";
}

// Validação antes do envio
function validarForm(usuariosAutorizados, usuarioLogado){
  if(!usuarioLogado || !usuariosAutorizados.includes(usuarioLogado)){ alert("Você não tem permissão."); return false; }
  const conteudoHTML=document.getElementById("editor").innerHTML.trim();
  if(!conteudoHTML){ alert("O conteúdo não pode estar vazio."); return false; }
  document.getElementById("conteudo").value=conteudoHTML;
  return true;
}
