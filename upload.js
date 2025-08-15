// Mostrar aba selecionada
function mostrarAba(id) {
  document.querySelectorAll('.aba').forEach(div => div.classList.add('hidden'));
  const aba = document.getElementById(id);
  if (aba) aba.classList.remove('hidden');
}

// Atualiza lista de telefones no HTML
function atualizarListaTelefones() {
  const lista = document.getElementById("listaTelefones");
  const clientes = JSON.parse(localStorage.getItem("clientes")) || [];
  lista.innerHTML = "";
  clientes.forEach(c => {
    const li = document.createElement("li");
    li.textContent = `${c.nome} - ${c.numero}`;
    lista.appendChild(li);
  });
}

// Importa arquivo CSV (Nome;Telefone)
function importarTelefones(file) {
  const reader = new FileReader();
  reader.onload = function(e) {
    const linhas = e.target.result.split("\n");
    const clientes = JSON.parse(localStorage.getItem("clientes")) || [];
    linhas.forEach(linha => {
      const [nome, numero] = linha.split(";");
      if(nome && numero){
        clientes.push({ nome: nome.trim(), numero: numero.trim() });
      }
    });
    localStorage.setItem("clientes", JSON.stringify(clientes));
    atualizarListaTelefones();
    alert("Telefones importados com sucesso!");
  }
  reader.readAsText(file);
}

// Evento do botão importar
document.getElementById("btnImportarTelefones").addEventListener("click", () => {
  const fileInput = document.getElementById("arquivoTelefones");
  if(fileInput.files.length === 0){
    alert("Selecione um arquivo para importar.");
    return;
  }
  importarTelefones(fileInput.files[0]);
});

// Inicializa lista ao carregar
document.addEventListener("DOMContentLoaded", atualizarListaTelefones);
