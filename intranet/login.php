<?php
// login.php

// Inicia uma sessão para controlar a autenticação do usuário
session_start();

// Lista simples de usuários permitidos. Em produção, use armazenamento
// seguro e senhas criptografadas.
$users = [
    'graziele.albuquerque' => 'Gringo1975',
    'gabriel.amaral'       => 'Gringo1975'
];

// Recupera os dados enviados pelo formulário de login
$user = $_POST['usuario'] ?? '';
$pass = $_POST['senha'] ?? '';

// Verifica se as credenciais conferem
if (isset($users[$user]) && $users[$user] === $pass) {
    // Armazena o usuário na sessão e redireciona para o painel
    $_SESSION['usuario'] = $user;
    header('Location: painel.html');
    exit;
}

// Em caso de falha, retorna para a página de login com indicação de erro
header('Location: index.html?erro=1');
exit;