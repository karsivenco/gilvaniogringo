<?php
// Dados de conexão com o banco da Locaweb
$host = 'localhost'; // ou 'mysql01.locawest.com.br' se seu plano usar host externo
$banco = 'gilvaniogringo'; // substitua pelo nome real do seu banco
$usuario = 'SEU_USUARIO_MYSQL'; // fornecido pela Locaweb no painel
$senha = 'SUA_SENHA_MYSQL';     // fornecida pela Locaweb no painel

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
