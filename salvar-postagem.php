<?php
header('Content-Type: application/json');
include 'conexao.php';

$titulo = $_POST['titulo'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$tipo = $_POST['tipo'] ?? 'rascunho';
$autor = $_POST['autor'] ?? 'Gilvani';

if(!$titulo || !$conteudo){
    echo json_encode(["sucesso" => false, "mensagem" => "Título e conteúdo são obrigatórios."]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (:titulo, :conteudo, :autor, :status)");
    $stmt->execute([
        ":titulo" => $titulo,
        ":conteudo" => $conteudo,
        ":autor" => $autor,
        ":status" => $tipo
    ]);
    echo json_encode(["sucesso" => true, "mensagem" => "Postagem salva com sucesso!", "tipo" => $tipo]);
} catch(PDOException $e){
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao salvar postagem: " . $e->getMessage()]);
}
?>
