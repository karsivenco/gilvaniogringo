<?php
session_start();

// Simula usuário logado (use $_SESSION normalmente no seu sistema real)
$usuarioLogado = $_SESSION['usuario'] ?? 'graziele.albuquerque';

// Caminho do arquivo de dados
$arquivo = __DIR__ . "/dados/perfis.json";

if (!file_exists($arquivo)) {
    die("Arquivo de perfis não encontrado.");
}

$perfis = json_decode(file_get_contents($arquivo), true);

if (!isset($perfis[$usuarioLogado])) {
    die("Perfil não encontrado.");
}

$perfil = $perfis[$usuarioLogado];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Intranet do Gringo - Perfil</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-inter min-h-screen flex flex-col">

  <!-- Cabeçalho -->
  <header class="bg-[#004766] shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
      <div class="flex items-center gap-3">
        <img src="img/logo.png" alt="Logo" class="h-10" />
        <h1 class="text-2xl font-semibold text-white">Intranet do Gringo</h1>
      </div>
      <div class="flex items-center gap-4 text-sm text-white">
        <a href="painel.html" class="hover:underline px-2">Voltar</a>
        <a href="nova-postagem.html" class="hover:underline px-2">+ Nova Postagem</a>
        <button onclick="logout()" class="hover:underline px-2">Sair</button>
      </div>
    </div>
  </header>

  <!-- Conteúdo -->
  <main class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-6 text-[#004766]">👤 Meu Perfil</h2>

    <?php if (isset($_GET['sucesso'])): ?>
      <div class="mb-4 p-3 bg-green-100 text-green-800 border border-green-300 rounded">
        ✅ Perfil atualizado com sucesso!
      </div>
    <?php elseif (isset($_GET['erro'])): ?>
      <div class="mb-4 p-3 bg-red-100 text-red-800 border border-red-300 rounded">
        ❌ Erro ao salvar o perfil. Verifique os dados e tente novamente.
      </div>
    <?php endif; ?>

    <form action="salvar-perfil.php" method="post" class="grid gap-4">
      <div>
        <label for="usuario" class="text-sm font-medium text-gray-700">Usuário</label>
        <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($perfil['usuario']) ?>" class="w-full border px-4 py-2 rounded bg-white" required />
      </div>

      <div>
        <label for="nome" class="text-sm font-medium text-gray-700">Nome completo</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($perfil['nome']) ?>" class="w-full border px-4 py-2 rounded bg-white" required />
      </div>

      <div>
        <label for="email" class="text-sm font-medium text-gray-700">E-mail</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($perfil['email']) ?>" class="w-full border px-4 py-2 rounded bg-white" required />
      </div>

      <div class="pt-4">
        <button type="submit" class="bg-[#004766] text-white px-6 py-2 rounded hover:bg-[#00334a] transition">
          ✔️ Concluído
        </button>
      </div>
    </form>
  </main>

  <script>
    function logout() {
      window.location.href = "logout.php";
    }
  </script>
</body>
</html>
