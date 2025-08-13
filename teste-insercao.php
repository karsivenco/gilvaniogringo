<?php
include 'conexao.php';

$sql = "INSERT INTO postagens (titulo, conteudo, autor, status, data_publicacao) 
        VALUES ('Teste Post', 'Conteúdo de teste', 'gilvaniogringo', 'rascunho', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "Inserção de teste realizada com sucesso!";
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>
