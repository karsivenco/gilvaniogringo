<?php
// logout.php

// Inicia a sessão e destrói para efetuar logout
session_start();
session_unset();
session_destroy();

// Redireciona de volta para a tela de login
header('Location: intranet.html');
exit;