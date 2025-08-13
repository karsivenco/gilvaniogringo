<?php
header('Content-Type: application/json');
include 'conexao.php';

$stmt = $pdo->prepare("SELECT * FROM postagens WHERE status='publicado' ORDER BY data_publicacao DESC LIMIT 5");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
?>
