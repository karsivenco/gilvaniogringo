<?php
session_start();

// Verifica se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: intranet.html");
    exit;
}

// Dados enviados via POST
$usuario = trim($_POST['usuario'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validação simples
if ($usuario === '' || $nome === '' || $email === '') {
    header("Location: perfil.php?erro=1");
    exit;
}

// Caminho do banco de dados
$arquivo = __DIR__ . '/dados/perfis.json';

// Verifica se arquivo existe; senão cria array vazio
if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $perfis = json_decode($json, true);
    if (!is_array($perfis)) {
        $perfis = [];
    }
} else {
    $perfis = [];
}

// Atualiza ou cria o perfil
$perfis[$usuario] = [
    'usuario' => $usuario,
    'nome' => $nome,
    'email' => $email
];

// Salva no arquivo JSON
$jsonNovo = json_encode($perfis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if (file_put_contents($arquivo, $jsonNovo) === false) {
    header("Location: perfil.php?erro=2");
    exit;
}

// Atualiza sessão
$_SESSION['usuario'] = $usuario;
$_SESSION['nome'] = $nome;
$_SESSION['email'] = $email;

// Redireciona com sucesso
header("Location: perfil.php?sucesso=1");
exit;
