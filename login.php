<?php
session_start();

$usuarios = [
  "graziele.albuquerque" => [
    "senha" => "Gringo1975",
    "nome" => "Graziele Albuquerque",
    "email" => "albuquerquegraziele916@gmail.com"
  ],
  "gabriel.amaral" => [
    "senha" => "Gringo1975",
    "nome" => "Gabriel Amaral",
    "email" => "ggabi722@gmail.com"
  ]
];

$usuario = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';

if (isset($usuarios[$usuario]) && $usuarios[$usuario]['senha'] === $senha) {
  $_SESSION['usuario'] = $usuario;
  $_SESSION['nome'] = $usuarios[$usuario]['nome'];
  $_SESSION['email'] = $usuarios[$usuario]['email'];

  // Armazenar também no sessionStorage com JS
  echo "<script>
    sessionStorage.setItem('usuario', '" . addslashes($usuario) . "');
    sessionStorage.setItem('nome', '" . addslashes($usuarios[$usuario]['nome']) . "');
    sessionStorage.setItem('email', '" . addslashes($usuarios[$usuario]['email']) . "');
    window.location.href = 'painel.html';
  </script>";
  exit;
} else {
  $_SESSION['erro'] = "Usuário ou senha incorretos.";
  header("Location: intranet.html");
  exit;
}
?>
