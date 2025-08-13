<?php
include 'conexao.php';

$sql = "SELECT * FROM postagens WHERE status='rascunho' ORDER BY data_publicacao DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<h2>".$row['titulo']."</h2>";
        echo "<p>".$row['conteudo']."</p>";
        echo "<small>Autor: ".$row['autor']." | Data: ".$row['data_publicacao']."</small><hr>";
    }
} else {
    echo "Nenhum rascunho encontrado.";
}

$conn->close();
?>
