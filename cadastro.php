<?php
header('Content-Type: application/json');
include 'conexao.php';

// Recebendo dados do formulário
$nome = $_POST['nome'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';
$perfil = $_POST['perfil'] ?? '';

// Validação básica
if(!$nome || !$usuario || !$email || !$senha || !$perfil){
    echo json_encode(["sucesso" => false, "mensagem" => "Todos os campos são obrigatórios."]);
    exit;
}

// Verifica se o usuário já existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario OR email = :email");
$stmt->execute([
    ":usuario" => $usuario,
    ":email" => $email
]);

if($stmt->rowCount() > 0){
    echo json_encode(["sucesso" => false, "mensagem" => "Usuário ou e-mail já cadastrado."]);
    exit;
}

// Criptografando a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Inserindo usuário no banco
try {
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil, usuario) VALUES (:nome, :email, :senha, :perfil, :usuario)");
    $stmt->execute([
        ":nome" => $nome,
        ":email" => $email,
        ":senha" => $senhaHash,
        ":perfil" => $perfil,
        ":usuario" => $usuario
    ]);
    echo json_encode(["sucesso" => true, "mensagem" => "Usuário cadastrado com sucesso!"]);
} catch(PDOException $e){
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao cadastrar usuário: " . $e->getMessage()]);
}
?>
