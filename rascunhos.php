<?php
include 'conexao.php';
session_start();

// Simulando usuário logado (você pode substituir pelo seu sistema de login)
$usuarioLogado = isset($_SESSION['usuarioLogado']) ? $_SESSION['usuarioLogado'] : 'gilvaniogringo';
$usuariosAutorizados = ["gabriel.amaral", "graziele.albuquerque", "karina.maia", "cristiano.santos", "gilvaniogringo"];
$usuariosExcluir = ["karina.maia", "cristiano.santos", "gilvaniogringo"];

if (!in_array($usuarioLogado, $usuariosAutorizados)) {
    die("Acesso negado.");
}

// Publicar rascunho
if (isset($_GET['publicar'])) {
    $id = intval($_GET['publicar']);
    $stmt = $conn->prepare("UPDATE postagens SET status='publicado', data_publicacao=NOW() WHERE idPrimária=? AND autor=?");
    $stmt->bind_param("is", $id, $usuarioLogado);
    $stmt->execute();
    header("Location: rascunhos.php");
    exit;
}

// Excluir rascunho
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $stmt = $conn->prepare("DELETE FROM postagens WHERE idPrimária=? AND autor=? AND status='rascunho'");
    $stmt->bind_param("is", $id, $usuarioLogado);
    $stmt->execute();
    header("Location: rascunhos.php");
    exit;
}

// Buscar rascunhos do usuário
$stmt = $conn->prepare("SELECT idPrimária, titulo, conteudo, autor, data_publicacao FROM postagens WHERE autor=? AND status='rascunho' ORDER BY data_publicacao DESC");
$stmt->bind_param("s", $usuarioLogado);
$stmt->execute();
$result = $stmt->get_result();
$rascunhos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Rascunhos - Intranet do Gringo</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
<style>
  .btn-acao { cursor: pointer; padding: 0.4rem 0.6rem; border-radius: 0.375rem; font-weight: 600; border: none; color: white; transition: background-color 0.2s ease-in-out; }
  .btn-excluir { background-color: #dc2626; }
  .btn-excluir:hover { background-color: #b91c1c; }
  .btn-editar { background-color: #2563eb; }
  .btn-editar:hover { background-color: #1e40af; }
  .btn-publicar { background-color: #16a34a; }
  .btn-publicar:hover { background-color: #166534; }
  #rascunhosContainer > div { max-width: 700px; margin: 0 auto; }
</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<header class="flex justify-between items-center bg-[#09679c] text-white p-4 shadow-md">
  <div class="flex items-center gap-3">
    <img src="img/logo.png" alt="Logo Gringo" class="h-10 w-auto" />
    <h1 id="tituloPagina" class="text-xl font-bold">Rascunhos</h1>
  </div>
  <nav class="flex items-center gap-3">
    <a href="painel.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1" title="Painel"><i class="fas fa-home"></i> Painel</a>
    <a href="nova-postagem.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1" title="Nova Postagem"><i class="fas fa-plus"></i> Nova Postagem</a>
    <a href="publicacoes.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1" title="Últimas Publicações"><i class="fas fa-newspaper"></i> Últimas Publicações</a>
    <a href="rascunhos.php" class="bg-[#074d6b] border border-white px-3 py-2 rounded bg-opacity-90 flex items-center gap-1" title="Rascunhos"><i class="fas fa-file-alt"></i> Rascunhos</a>
    <a href="perfil.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1" title="Perfil"><i class="fas fa-user"></i> Perfil</a>
    <button onclick="logout()" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition flex items-center gap-1" title="Sair"><i class="fas fa-sign-out-alt"></i> Sair</button>
  </nav>
</header>

<main class="flex-grow p-6 max-w-5xl mx-auto bg-white rounded shadow mt-6 mb-10">
  <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
    <h2 class="text-lg font-bold text-[#09679c] mb-2">Rascunhos:</h2>
    <p class="text-gray-700 text-sm">Aqui ficam armazenadas as postagens que você iniciou mas ainda não publicou. Você pode editar, excluir ou publicar diretamente qualquer rascunho listado abaixo.</p>
  </div>

  <section id="rascunhosContainer" class="space-y-6">
    <?php if(count($rascunhos) === 0): ?>
      <p>Sem rascunhos</p>
    <?php else: ?>
      <?php foreach($rascunhos as $r): ?>
        <div class="border rounded p-4 shadow-sm flex justify-between items-center">
          <div>
            <h3 class="text-xl font-bold mb-1 text-[#09679c]"><?= htmlspecialchars($r['titulo']) ?></h3>
            <p class="text-gray-600 text-sm">Data: <?= date("d/m/Y H:i", strtotime($r['data_publicacao'])) ?></p>
            <p class="text-gray-600 text-sm">Autor: <?= htmlspecialchars($r['autor']) ?></p>
          </div>
          <div class="flex gap-2">
            <a href="nova-postagem.php?id=<?= $r['idPrimária'] ?>" class="btn-acao btn-editar" title="Editar"><i class="fas fa-edit"></i></a>
            <a href="?publicar=<?= $r['idPrimária'] ?>" class="btn-acao btn-publicar" title="Publicar" onclick="return confirm('Deseja publicar este rascunho?')"><i class="fas fa-paper-plane"></i></a>
            <?php if(in_array($usuarioLogado, $usuariosExcluir)): ?>
              <a href="?excluir=<?= $r['idPrimária'] ?>" class="btn-acao btn-excluir" title="Excluir" onclick="return confirm('Deseja realmente excluir este rascunho?')"><i class="fas fa-trash"></i></a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<script>
function logout() {
    fetch('logout.php').then(() => window.location.href = 'intranet.html');
}
</script>

</body>
</html>
