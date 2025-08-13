<?php
$servername = "gilvaniogringo.mysql.dbaas.com.br";
$username = "gilvaniogringo";  // usuário correto
$password = "Gringo@20";       // senha correta
$dbname = "gilvaniogringo";

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao conectar com o servidor: ' . $e->getMessage()
    ]));
}
?>
