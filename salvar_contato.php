<?php
header('Content-Type: application/json');
include 'conexao.php';

// Recebe dados do formulário (POST)
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$mensagem = $_POST['mensagem'] ?? '';
$municipio = $_POST['municipio'] ?? '';
$bairro = $_POST['bairro'] ?? '';

if(!$nome || !$email || !$mensagem){
    echo json_encode(["sucesso" => false, "mensagem" => "Todos os campos obrigatórios."]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO contatos (nome, email, mensagem, municipio, bairro) 
                           VALUES (:nome, :email, :mensagem, :municipio, :bairro)");
    $stmt->execute([
        ":nome"      => $nome,
        ":email"     => $email,
        ":mensagem"  => $mensagem,
        ":municipio" => $municipio,
        ":bairro"    => $bairro
    ]);
    echo json_encode(["sucesso" => true, "mensagem" => "Contato enviado com sucesso!"]);
} catch(PDOException $e){
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao enviar contato: " . $e->getMessage()]);
}
?>
