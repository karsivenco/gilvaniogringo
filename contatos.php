<?php
header('Content-Type: application/json');
include 'conexao.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';

if(!$nome || !$email || !$mensagem){
    echo json_encode(["sucesso" => false, "mensagem" => "Todos os campos são obrigatórios."]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)");
    $stmt->execute([
        ":nome" => $nome,
        ":email" => $email,
        ":mensagem" => $mensagem
    ]);
    echo json_encode(["sucesso" => true, "mensagem" => "Contato enviado com sucesso!"]);
} catch(PDOException $e){
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao enviar contato: " . $e->getMessage()]);
}
?>
