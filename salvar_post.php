<?php
include 'conexao.php';
header('Content-Type: application/json');

$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$status = $_POST['status'] ?? 'rascunho';
$autor = 'gilvaniogringo';

if (!$titulo || !$conteudo) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Título ou conteúdo vazio.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (:titulo, :conteudo, :autor, :status)");
    $stmt->execute([
        ':titulo' => $titulo,
        ':conteudo' => $conteudo,
        ':autor' => $autor,
        ':status' => $status
    ]);
    echo json_encode(['sucesso' => true, 'mensagem' => 'Post salvo!', 'status' => $status]);
} catch(PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
}
?>
