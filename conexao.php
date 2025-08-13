<?php
// conexao.php
$host = "gilvaniogringo.mysql.dbaas.com.br";
$db   = "gilvaniogringo";
$user = "gilvaniogringo";
$pass = "Gringo@20";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["sucesso" => false, "mensagem" => "Erro ao conectar: " . $e->getMessage()]));
}
?>
