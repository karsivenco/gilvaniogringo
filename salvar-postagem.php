<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo   = trim($_POST['titulo'] ?? '');
    $conteudo = trim($_POST['conteudo'] ?? '');
    $tipo     = $_POST['tipo'] ?? 'rascunho';
    $status   = $tipo === 'publicar' ? 'publicado' : 'rascunho';
    $autor    = "Gilvani";

    if (empty($titulo) || empty($conteudo)) {
        echo json_encode(["sucesso" => false, "mensagem" => "Título e conteúdo são obrigatórios."]);
        exit;
    }

    try {
        // grava no banco
        $sql = "INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (:titulo, :conteudo, :autor, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":titulo"   => $titulo,
            ":conteudo" => $conteudo,
            ":autor"    => $autor,
            ":status"   => $status
        ]);

        // gera JSON atualizado
        $query = $pdo->query("SELECT id, titulo, conteudo, autor, status, data_publicacao FROM postagens ORDER BY data_publicacao DESC");
        $postagens = $query->fetchAll(PDO::FETCH_ASSOC);

        $jsonPath = __DIR__ . "/gilvaniogringo/dados/postagens.json";
        if (!is_dir(dirname($jsonPath))) {
            mkdir(dirname($jsonPath), 0777, true);
        }
        file_put_contents($jsonPath, json_encode($postagens, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        echo json_encode([
            "sucesso"  => true,
            "mensagem" => $status === 'publicado' ? "Post publicado com sucesso!" : "Rascunho salvo com sucesso!",
            "tipo"     => $status
        ]);
    } catch (PDOException $e) {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro no banco: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Requisição inválida"]);
}
