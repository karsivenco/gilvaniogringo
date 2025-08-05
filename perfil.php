<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: intranet.html");
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($usuario === '' || $nome === '' || $email === '') {
    header("Location: perfil.php?erro=1");
    exit;
}

$caminhoArquivo = __DIR__ . "/dados/perfis.json";

// Lê arquivo atual, se existir
$perfis = [];
if (file_exists($caminhoArquivo)) {
    $json = file_get_contents($caminhoArquivo);
    $perfis = json_decode($json, true);
    if (!is_array($perfis)) {
        $perfis = [];
    }
}

// Atualiza ou adiciona o perfil do usuário
$perfis[$usuario] = [
    'usuario' => $usuario,
    'nome' => $nome,
    'email' => $email,
];

// Salva arquivo JSON atualizado
$jsonNovo = json_encode($perfis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if (file_put_contents($caminhoArquivo, $jsonNovo) === false) {
    header("Location: perfil.php?erro=2");
    exit;
}

// Atualiza sessão
$_SESSION['usuario'] = $usuario;
$_SESSION['nome'] = $nome;
$_SESSION['email'] = $email;

header("Location: perfil.php?sucesso=1");
exit;
