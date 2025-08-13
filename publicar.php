<?php
header('Content-Type: application/json');
include 'conexao.php';

$id = $_POST['id'] ?? 0;

if(!$id){
    echo json_encode(["sucesso" => false, "mensagem" => "ID do post é obrigatório."]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE postagens SET status='publicado' WHERE idPrimária=:id");
    $stmt->execute([":id" => $id]);
    echo json_encode(["sucesso" => true, "mensagem" => "Postagem publicada!"]);
} catch(PDOException $e){
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao publicar: " . $e->getMessage()]);
}
?>
