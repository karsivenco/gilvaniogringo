<?php
session_start();
$users = [
    'graziele.albuquerque' => 'Gringo1975',
    'gabriel.amaral' => 'Gringo1975'
];

$user = $_POST['usuario'] ?? '';
$pass = $_POST['senha'] ?? '';
if (isset($users[$user]) && $users[$user] === $pass) {
    $_SESSION['usuario'] = $user;
    header('Location: painel.html');
    exit;
}
header('Location: intranet.html?erro=1');
exit;
?>
