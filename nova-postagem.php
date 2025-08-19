<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexao.php'; // já contém a conexão PDO

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["sucesso" => false, "mensagem" => "Requisição inválida"]);
    exit;
}

// Coleta dados enviados via POST
$titulo   = trim($_POST['titulo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');
$tipo     = $_POST['tipo'] ?? 'rascunho';
$status   = $tipo === 'publicar' ? 'publicado' : 'rascunho';
$autor    = "Gilvani"; // fixo, pode ajustar conforme login

// Validação simples
if (!$titulo || !$conteudo) {
    echo json_encode(["sucesso" => false, "mensagem" => "Título e conteúdo são obrigatórios."]);
    exit;
}

try {
    $sql = "INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (:titulo, :conteudo, :autor, :status)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":titulo"   => $titulo,
        ":conteudo" => $conteudo,
        ":autor"    => $autor,
        ":status"   => $status
    ]);

    echo json_encode([
        "sucesso"  => true,
        "mensagem" => $status === 'publicado' ? "Post publicado com sucesso!" : "Rascunho salvo com sucesso!",
        "tipo"     => $status
    ]);
} catch (PDOException $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco: " . $e->getMessage()]);
}
?>
