<?php
// check_session.php

// Este endpoint retorna um JSON indicando se há um usuário logado na sessão.
session_start();
header('Content-Type: application/json');
echo json_encode([
    'authenticated' => isset($_SESSION['usuario'])
]);