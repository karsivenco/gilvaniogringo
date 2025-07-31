<?php
// check_session.php

// Retorna um JSON simples indicando se há um usuário logado
session_start();
header('Content-Type: application/json');
echo json_encode([
    'authenticated' => isset($_SESSION['usuario'])
]);