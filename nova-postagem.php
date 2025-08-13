<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $status = $_POST['status'] ?? 'rascunho';
    $autor = 'Gilvani'; // autor fixo

    $stmt = $conn->prepare("INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $conteudo, $autor, $status);

    if ($stmt->execute()) {
        echo "Postagem criada com sucesso! <a href='listar_postagens.php'>Ver postagens</a>";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}
?>

<h2>Criar nova postagem</h2>
<form method="POST" action="">
    <input type="text" name="titulo" placeholder="Título" required><br><br>
    <textarea name="conteudo" placeholder="Conteúdo" required></textarea><br><br>
    <select name="status">
        <option value="publicado">Publicado</option>
        <option value="rascunho">Rascunho</option>
    </select><br><br>
    <button type="submit">Publicar</button>
</form>
