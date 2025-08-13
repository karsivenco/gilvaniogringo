<?php
session_start();
include 'conexao.php';

// Usuário logado
$usuarioLogado = isset($_SESSION['usuarioLogado']) ? $_SESSION['usuarioLogado'] : null;

// Usuários autorizados a publicar
$usuariosAutorizados = [
    "gabriel.amaral",
    "graziele.albuquerque",
    "karina.maia",
    "cristiano.santos",
    "gilvaniogringo"
];

if (!$usuarioLogado || !in_array($usuarioLogado, $usuariosAutorizados)) {
    die("Você não tem permissão para publicar.");
}

// Receber ID do rascunho
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID inválido.");
}

// Atualizar status no banco
$stmt = $conn->prepare("UPDATE postagens SET status = 'publicado', data_publicacao = NOW() WHERE idPrimária = ? AND autor = ?");
$stmt->bind_param("is", $id, $usuarioLogado);

if ($stmt->execute()) {
    $stmt->close();
    // Redirecionar para rascunhos ou publicações
    header("Location: publicacoes.php");
    exit;
} else {
    die("Erro ao publicar o rascunho: " . $stmt->error);
}
