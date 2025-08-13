<?php
include 'conexao.php';

header('Content-Type: application/json');

$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$autor = 'gilvaniogringo'; // ou recuperar do session/localStorage

if (!$titulo || !$conteudo) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Título ou conteúdo vazio.']);
    exit;
}

$status = ($tipo === 'rascunho') ? 'rascunho' : 'publicado';

try {
    $stmt = $conn->prepare("INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (:titulo, :conteudo, :autor, :status)");
    $stmt->execute([
        ':titulo' => $titulo,
        ':conteudo' => $conteudo,
        ':autor' => $autor,
        ':status' => $status
    ]);

    echo json_encode([
        'sucesso' => true,
        'mensagem' => ($status === 'rascunho') ? 'Rascunho salvo!' : 'Post publicado!',
        'tipo' => $tipo
    ]);
} catch(PDOException $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar postagem: ' . $e->getMessage()]);
}
?>
