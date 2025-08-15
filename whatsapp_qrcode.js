// whatsapp_qrcode.js

// Estado inicial
window.jsconfig = window.jsconfig || {
  generate_qr: false,
  whatsapp_disconnected: true,
  ws_pk: "gilvani-o-gringo"
};

let qrCodeTimeout = null;

// Seletores
const qrContainer = document.getElementById("qrcode_image_outer");
const btnGenerate = document.querySelector(".js-wa-connect");
const btnDisconnect = document.querySelector(".js-wa-disconnect");
const statusOuter = document.getElementById("whatsapp_status_outer");

// Função para gerar QR code
function gerarQRCode() {
  if (!qrContainer) return;

  qrContainer.innerHTML = ""; // Limpa QR antigo
  statusOuter.innerHTML = "Gerando QR Code...";

  // Exemplo usando a lib QRCode.js
  QRCode.toCanvas(qrContainer, "https://web.whatsapp.com", function (error) {
    if (error) {
      console.error(error);
      statusOuter.innerHTML = "Erro ao gerar QR Code";
    } else {
      statusOuter.innerHTML = "QR Code gerado. Escaneie no WhatsApp";
      qrContainer.parentElement.classList.remove("hidden");
    }
  });
}

// Função para desconectar WhatsApp
function desconectarWhatsapp() {
  statusOuter.innerHTML = "Conta desconectada";
  qrContainer.innerHTML = "";
  qrContainer.parentElement.classList.add("hidden");
  console.log("WhatsApp desconectado");
}

// Eventos
if (btnGenerate) {
  btnGenerate.addEventListener("click", gerarQRCode);
}

if (btnDisconnect) {
  btnDisconnect.addEventListener("click", desconectarWhatsapp);
}

// Exporta funções se necessário
window.WhatsappQRCode = {
  gerarQRCode,
  desconectarWhatsapp
};
