<?php
// logout.php

// Finaliza a sessão do usuário e redireciona para a tela de login
session_start();
session_unset();
session_destroy();

header('Location: index.html');
exit;