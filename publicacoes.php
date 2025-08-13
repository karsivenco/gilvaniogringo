<?php
include 'conexao.php';
session_start();

// Usuário logado (substitua pelo seu sistema de login)
$usuarioLogado = isset($_SESSION['usuarioLogado']) ? $_SESSION['usuarioLogado'] : 'gilvaniogringo';
$usuariosAutorizados = ["gabriel.amaral", "graziele.albuquerque", "karina.maia", "cristiano.santos", "gilvaniogringo"];
$usuariosExcluir = ["karina.maia", "cristiano.santos", "gilvaniogringo"];

if (!in_array($usuarioLogado, $usuariosAutorizados)) {
    die("Acesso negado.");
}

// Excluir publicação
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $stmt = $conn->prepare("DELETE FROM postagens WHERE idPrimária=? AND autor=? AND status='publicado'");
    $stmt->bind_param("is", $id, $usuarioLogado);
    $stmt->execute();
    header("Location: publicacoes.php");
    exit;
}

// Buscar publicações publicadas
$stmt = $conn->prepare("SELECT idPrimária, titulo, conteudo, autor, data_publicacao FROM postagens WHERE status='publicado' ORDER BY data_publicacao DESC");
$stmt->execute();
$result = $stmt->get_result();
$publicacoes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Publicações - Intranet do Gringo</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
<style>
.resumo { white-space: pre-wrap; }
.btn-expandir { cursor: pointer; color: #09679c; font-weight: 600; user-select: none; }
.btn-excluir { background-color: #dc2626; color: white; padding: 0.4rem 0.6rem; border-radius: 0.375rem; border: none; margin-top: 8px; }
.btn-excluir:hover { background-color: #b91c1c; }
</style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<header class="flex justify-between items-center bg-[#09679c] text-white p-4 shadow-md">
  <div class="flex items-center gap-3">
    <img src="img/logo.png" alt="Logo Gringo" class="h-10 w-auto" />
    <h1 class="text-xl font-bold">Publicações</h1>
  </div>
  <nav class="flex items-center gap-3">
    <a href="painel.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1"><i class="fas fa-home"></i> Painel</a>
    <a href="nova-postagem.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1"><i class="fas fa-plus"></i> Nova Postagem</a>
    <a href="publicacoes.php" class="bg-[#074d6b] border border-white px-3 py-2 rounded bg-opacity-90 flex items-center gap-1"><i class="fas fa-newspaper"></i> Últimas Publicações</a>
    <a href="rascunhos.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1"><i class="fas fa-file-alt"></i> Rascunhos</a>
    <a href="perfil.php" class="bg-[#09679c] border border-white px-3 py-2 rounded hover:bg-[#074d6b] transition flex items-center gap-1"><i class="fas fa-user"></i> Perfil</a>
    <button onclick="logout()" class="bg-[#053646] px-3 py-2 rounded hover:bg-[#032d39] transition flex items-center gap-1"><i class="fas fa-sign-out-alt"></i> Sair</button>
  </nav>
</header>

<main class="flex-grow p-6 max-w-5xl mx-auto bg-white rounded shadow mt-6 mb-10">
  <section id="publicacoesContainer" class="space-y-8">
    <?php
    if(count($publicacoes) === 0) {
        echo "<p>Nenhuma publicação encontrada.</p>";
    } else {
        // Agrupar por autor
        $grupos = [];
        foreach($publicacoes as $p) {
            $grupos[$p['autor']][] = $p;
        }

        foreach($grupos as $autor => $pubs) {
            echo '<section class="mb-10">';
            echo '<h2 class="text-2xl font-bold text-[#005075] border-b-4 border-[#FF7DA4] pb-2 mb-6">Publicações de '.$autor.'</h2>';
            foreach($pubs as $pub) {
                $conteudoLimpo = strip_tags($pub['conteudo']);
                $maxResumo = 200;
                $resumo = (strlen($conteudoLimpo) > $maxResumo) ? substr($conteudoLimpo,0,$maxResumo)."..." : $conteudoLimpo;
                echo '<div class="border rounded p-4 shadow-sm">';
                echo '<h3 class="text-xl font-bold mb-2 text-[#09679c]">'.htmlspecialchars($pub['titulo']).'</h3>';
                echo '<p class="text-gray-600 text-sm mb-2">Data/Hora: '.date("d/m/Y H:i", strtotime($pub['data_publicacao'])).'</p>';
                echo '<p class="resumo mb-2" id="resumo-'.$pub['idPrimária'].'">'.$resumo.'</p>';
                if(strlen($conteudoLimpo) > $maxResumo){
                    echo '<span class="btn-expandir" onclick="toggleConteudo('.$pub['idPrimária'].')">(mostrar mais)</span>';
                }
                if(in_array($usuarioLogado, $usuariosExcluir)){
                    echo '<a href="?excluir='.$pub['idPrimária'].'" class="btn-excluir" onclick="return confirm(\'Deseja realmente excluir esta publicação?\')">Excluir</a>';
                }
                echo '</div>';
            }
            echo '</section>';
        }
    }
    ?>
  </section>
</main>

<script>
function logout() {
    fetch('logout.php').then(() => window.location.href='intranet.html');
}

// Toggle "mostrar mais/menos"
const expandido = {};
function toggleConteudo(id){
    const p = document.getElementById('resumo-'+id);
    if(!expandido[id]){
        p.textContent = p.getAttribute('data-full') || p.textContent;
        expandido[id] = true;
    } else {
        const txt = p.getAttribute('data-resumo') || p.textContent;
        p.textContent = txt;
        expandido[id] = false;
    }
}
</script>

</body>
</html>
