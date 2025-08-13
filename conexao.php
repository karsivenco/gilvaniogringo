<?php
$host = "gilvaniogringo.mysql.dbaas.com.br";  // seu servidor
$db   = "gilvaniogringo";                     // seu banco de dados
$user = "gilvaniogringo";                     // seu usuário
$pass = "Gringo@20";                          // sua senha

// Criar conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
// echo "Conexão bem-sucedida!";
?>
