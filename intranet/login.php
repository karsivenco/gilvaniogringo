<?php
// login.php

// Inicia a sessão para armazenar o usuário logado
session_start();

// Usuários permitidos e suas senhas. Para aumentar a segurança em produção,
// use senhas criptografadas e mova essas credenciais para um arquivo seguro.
$users = [
    'graziele.albuquerque' => 'Gringo1975',
    'gabriel.amaral'       => 'Gringo1975'
];

// Recupera credenciais do formulário
$user = $_POST['usuario'] ?? '';
$pass = $_POST['senha'] ?? '';

// Verifica se o usuário existe e a senha confere
if (isset($users[$user]) && $users[$user] === $pass) {
    $_SESSION['usuario'] = $user;
    header('Location: painel.html');
    exit;
}

// Caso contrário, retorna para a página de login com parâmetro de erro
header('Location: intranet.html?erro=1');
exit;