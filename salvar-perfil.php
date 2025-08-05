<?php
session_start();

// Verifica se usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: intranet.html");
    exit;
}

// Pega dados do formulário
$usuario = trim($_POST['usuario'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validação básica
if ($usuario === '' || $nome === '' || $email === '') {
    // Redirecionar com erro (pode melhorar depois)
    header("Location: perfil.php?erro=1");
    exit;
}

// Monta array de dados para salvar
$dadosPerfil = [
    'usuario' => $usuario,
    'nome' => $nome,
    'email' => $email,
];

// Caminho do arquivo JSON para o usuário (ex: dados/usuario.json)
$caminhoArquivo = __DIR__ . "/dados/{$usuario}.json";

// Salva os dados em JSON no arquivo
$json = json_encode($dadosPerfil, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($caminhoArquivo, $json) === false) {
    // Erro ao salvar
    header("Location: perfil.php?erro=2");
    exit;
}

// Atualiza variáveis de sessão
$_SESSION['usuario'] = $usuario;
$_SESSION['nome'] = $nome;
$_SESSION['email'] = $email;

// Redireciona de volta para perfil.php com sucesso
header("Location: perfil.php?sucesso=1");
exit;
