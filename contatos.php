<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
$usuarioLogado = isset($_SESSION['usuarioLogado']) ? $_SESSION['usuarioLogado'] : null;
if (!$usuarioLogado) {
    header("Location: intranet.html");
    exit;
}

// Buscar contatos do banco
$sql = "SELECT * FROM contatos ORDER BY nome ASC";
$result = $conn->query($sql);
$contatos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $contatos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contatos - Intranet do Gringo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- Header padrão -->
  <header class="flex justify-between items-center bg-[#09679c] text-white p-4 shadow-md">
    <div class="flex items-center gap-3">
      <img src="img/logo.png" alt="Logo Gringo" class="h-10 w-auto" />
      <h1 class="text-xl font-bold">Contatos</h1>
    </div>
    <nav class="flex items-center gap-3">
      <a href="painel.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition">Início</a>
      <a href="cadastro.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition">Cadastro</a>
      <a href="contatos.php" class="bg-[#074d6b] border border-white px-3 py-2 rounded transition">Contatos</a>
      <a href="perfil.html" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition">Perfil</a>
      <?php if($usuarioLogado === "gilvaniogringo"): ?>
      <a href="dashboard.html" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition">Dashboard</a>
      <?php endif; ?>
      <a href="logout.php" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition">Sair</a>
    </nav>
  </header>

  <!-- Conteúdo -->
  <main class="flex-grow p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-[#005075]">Lista de Contatos</h2>

    <div class="bg-white shadow rounded-lg p-6">
      <ul class="divide-y divide-gray-200">
        <?php if(count($contatos) === 0): ?>
          <li class="py-4 text-gray-500 italic">Nenhum contato encontrado.</li>
        <?php else: ?>
          <?php foreach($contatos as $c): ?>
            <li class="py-4 flex flex-col md:flex-row md:justify-between md:items-center">
              <div class="mb-2 md:mb-0">
                <p><strong class="text-[#09679c]"><?= htmlspecialchars($c['nome']) ?></strong></p>
                <p>Telefone: <?= htmlspecialchars($c['numero']) ?></p>
                <?php if(!empty($c['endereco'])): ?>
                  <p>Endereço: <?= htmlspecialchars($c['endereco']) ?></p>
                <?php endif; ?>
                <p>Origem: <em><?= htmlspecialchars($c['origem']) ?></em></p>
              </div>
              <div class="flex gap-3">
                <a href="excluir_contato.php?id=<?= $c['id'] ?>" 
                   onclick="return confirm('Deseja realmente excluir este contato?')"
                   class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                  <i class="fas fa-trash"></i> Excluir
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </main>

</body>
</html>
