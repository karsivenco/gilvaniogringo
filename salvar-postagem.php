<?php
// salvar-postagem.php
session_start();

// Conexão com o banco
$servername = "gilvaniogringo.mysql.dbaas.com.br";
$username = "gilvaniogringo";
$password = "Gringo@20";
$dbname = "gilvaniogringo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Captura dados do form
$titulo   = isset($_POST['titulo']) ? $conn->real_escape_string($_POST['titulo']) : '';
$conteudo = isset($_POST['conteudo']) ? $conn->real_escape_string($_POST['conteudo']) : '';
$tipo     = isset($_POST['tipo']) ? $_POST['tipo'] : 'rascunho';
$autor    = isset($_SESSION['usuarioLogado']) ? $_SESSION['usuarioLogado'] : 'desconhecido';

// Validação básica
if (empty($titulo) || empty($conteudo)) {
    $_SESSION['msgErro'] = "Título e conteúdo são obrigatórios.";
    header("Location: nova-postagem.html");
    exit();
}

// Define status
$status = ($tipo === 'publicar') ? 'publicado' : 'rascunho';

// Inserção no banco
$sql = "INSERT INTO postagens (titulo, conteudo, autor, status) VALUES ('$titulo', '$conteudo', '$autor', '$status')";
if ($conn->query($sql) === TRUE) {
    $_SESSION['msgSucesso'] = ($status === 'publicado') ? "Publicação realizada com sucesso!" : "Rascunho salvo com sucesso!";
    
    // Redirecionamento
    if ($status === 'rascunho') {
        header("Location: rascunhos.html");
        exit();
    } else {
        // Publicação → pode redirecionar para publicacoes.html
        header("Location: publicacoes.html");
        exit();
    }

} else {
    $_SESSION['msgErro'] = "Erro ao salvar postagem: " . $conn->error;
    header("Location: nova-postagem.html");
    exit();
}

$conn->close();
?>
