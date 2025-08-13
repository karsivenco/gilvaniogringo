<?php
$host = "localhost";
$db = "intranet_gringo";
$user = "gringo_user";
$pass = "senhaSegura123";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$titulo = $_POST['titulo'];
$conteudo = $_POST['conteudo'];
$autor = $_POST['autor'];
$status = $_POST['status'];

$sql = "INSERT INTO postagens (titulo, conteudo, autor, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $titulo, $conteudo, $autor, $status);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
