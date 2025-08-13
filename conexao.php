<?php
$host = "gilvaniogringo.mysql.dbaas.com.br";
$db   = "gilvaniogringo";
$user = "gilvaniogringo";
$pass = "Gringo@20";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao conectar com o servidor: " . $e->getMessage()
    ]);
    exit;
}
