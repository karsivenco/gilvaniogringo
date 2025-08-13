<?php
session_start();
include 'conexao.php';

// Simular usuário logado (ou buscar via session/cookie)
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

// Receber dados do formulário
$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$conteudo = isset($_POST['conteudo']) ? trim($_POST['conteudo']) : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'rascunho'; // rascunho ou publicar

if (empty($titulo) || empty($conteudo)) {
    die("Título e conteúdo são obrigatórios.");
}

// Determinar status
$status = ($tipo === 'publicar') ? 'publicado' : 'rascunho';

// Inserir no banco
$stmt = $conn->prepare("INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $titulo, $conteudo, $usuarioLogado, $status);

if ($stmt->execute()) {
    $idInserido = $stmt->insert_id;
    $stmt->close();

    // Redirecionar de acordo com o tipo
    if ($status === 'publicado') {
        header("Location: publicacoes.php");
        exit;
    } else {
        header("Location: rascunhos.php");
        exit;
    }
} else {
    die("Erro ao salvar a postagem: " . $stmt->error);
}
