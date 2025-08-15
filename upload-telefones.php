<?php
include 'conexao.php';

if(isset($_FILES['arquivoTelefones'])){
    $arquivo = $_FILES['arquivoTelefones']['tmp_name'];
    $handle = fopen($arquivo, "r");
    if ($handle !== FALSE) {
        while (($linha = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $nome = trim($linha[0]);
            $numero = trim($linha[1]);
            if($nome && $numero){
                $stmt = $conn->prepare("INSERT INTO clientes (nome, numero) VALUES (?, ?)");
                $stmt->bind_param("ss", $nome, $numero);
                $stmt->execute();
            }
        }
        fclose($handle);
        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Telefones importados com sucesso!']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Não foi possível abrir o arquivo']);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Arquivo não enviado']);
}
?>
