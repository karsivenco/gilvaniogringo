<?php
$host = "gilvaniogringo.mysql.dbaas.com.br";
$db   = "gilvaniogringo";
$user = "gilvaniogringo";
$pass = "Gringo@20";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
