<?php
include 'conexao.php';  // conecta ao banco real

// Recebe dados do formulário
$titulo = $_POST['titulo'];
$conteudo = $_POST['conteudo'];
$autor = $_POST['autor'];
$status = $_POST['status'];
$data = date("Y-m-d H:i:s");

// Insere no banco
$sql = "INSERT INTO postagens (titulo, conteudo, autor, status, data_publicacao)
        VALUES ('$titulo', '$conteudo', '$autor', '$status', '$data')";

if ($conn->query($sql) === TRUE) {
    echo "Postagem salva com sucesso!";
    // Opcional: redirecionar para rascunhos ou últimas notícias
    // header("Location: rascunhos.php");
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>
