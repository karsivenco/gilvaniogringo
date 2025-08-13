<?php
header('Content-Type: application/json');
include 'conexao.php';

$stmt = $pdo->prepare("SELECT * FROM postagens WHERE status='publicado' ORDER BY data_publicacao DESC");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($posts);
?>
