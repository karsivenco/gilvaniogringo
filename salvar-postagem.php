<?php
include 'conexao.php';

$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$tipo = $_POST['tipo'] ?? 'rascunho'; // 'rascunho' ou 'publicar'
$autor = $_POST['autor'] ?? 'Desconhecido';
$data = date('Y-m-d H:i:s');

$status = $tipo === 'publicar' ? 'publicado' : 'rascunho';

$stmt = $conn->prepare("INSERT INTO postagens (titulo, conteudo, autor, status, data_publicacao) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $titulo, $conteudo, $autor, $status, $data);

if ($stmt->execute()) {
    if ($tipo === 'publicar') {
        header("Location: publicacoes.html?msg=Publicação realizada com sucesso");
    } else {
        header("Location: rascunhos.html?msg=Rascunho salvo com sucesso");
    }
    exit;
} else {
    echo "Erro ao salvar postagem: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
