<?php
$host = 'gilvaniogringo.mysql.dbaas.com.br'; // servidor do Locaweb
$db   = 'gilvaniogringo';                   // nome do banco de dados
$user = 'gilvaniogringo';                   // usuário do banco
$pass = 'Gringo@20';                        // senha do banco
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch(PDOException $e) {
    // Retorna erro em JSON
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao conectar ao banco: " . $e->getMessage()]);
    exit;
}
?>
