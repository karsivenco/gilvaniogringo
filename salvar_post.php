<?php
header('Content-Type: application/json');
include 'conexao.php';

$postagem_id = $_POST['postagem_id'] ?? 0;

if(!$postagem_id || !isset($_FILES['img'])){
    echo json_encode(["sucesso" => false, "mensagem" => "Postagem e imagem são obrigatórios."]);
    exit;
}

$arquivo = $_FILES['img'];
$nome = $arquivo['name'];
$tipo = $arquivo['type'];
$dados = file_get_contents($arquivo['tmp_name']);

try {
    $stmt = $pdo->prepare("INSERT INTO img (postagem_id, nome, tipo, dados) VALUES (:postagem_id, :nome, :tipo, :dados)");
    $stmt->bindParam(":postagem_id", $postagem_id, PDO::PARAM_INT);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":tipo", $tipo);
    $stmt->bindParam(":dados", $dados, PDO::PARAM_LOB);
    $stmt->execute();

    echo json_encode(["sucesso" => true, "mensagem" => "Imagem enviada com sucesso!"]);
} catch(PDOException $e){
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao enviar imagem: " . $e->getMessage()]);
}
?>
