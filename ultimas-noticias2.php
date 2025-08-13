<?php
include 'conexao.php';

// Receber o id da notícia pela URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Notícia inválida.");
}

// Buscar notícia publicada no banco
$stmt = $conn->prepare("SELECT titulo, conteudo, DATE(data_publicacao) as data FROM postagens WHERE idPrimária=? AND status='publicado'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$noticia = $result->fetch_assoc();
$stmt->close();

if (!$noticia) {
    die("Notícia não encontrada.");
}
?>

<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">

<head>
  <meta charset="utf-8" />
  <title><?php echo htmlspecialchars($noticia['titulo']); ?> - Gilvani, o Gringo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">

  <section class="py-12 md:py-20" id="Materia">
    <div class="container mx-auto px-4">
      <div class="mx-auto max-w-3xl text-center">
        <h2 id="titulo" class="mb-4 text-3xl md:text-4xl font-bold text-[#005075]">
          <?php echo htmlspecialchars($noticia['titulo']); ?>
        </h2>
        <p id="data" class="mb-6 text-sm text-gray-500">
          <strong><?php echo date("d/m/Y", strtotime($noticia['data'])); ?></strong>
        </p>
      </div>

      <article id="texto" class="max-w-3xl mx-auto text-justify text-lg leading-relaxed text-gray-700">
        <?php echo $noticia['conteudo']; ?>
      </article>
    </div>
  </section>

</body>

</html>
