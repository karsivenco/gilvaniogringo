<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuarios = [
        "graziele.albuquerque" => ["email" => "albuquerquegraziele916@gmail.com", "senha" => "Gringo1975"],
        "gabriel.amaral" => ["email" => "ggabi722@gmail.com", "senha" => "Gringo1975"]
    ];

    $usuario = $_POST["usuario"];
    
    if (isset($usuarios[$usuario])) {
        $destinatario = $usuarios[$usuario]["email"];
        $senha = $usuarios[$usuario]["senha"];
        $assunto = "Recuperação de Senha - Intranet do Gringo";
        $mensagem = "Olá $usuario,\n\nConforme solicitado, aqui está sua senha atual para acesso à Intranet:\n\nSenha: $senha\n\nRecomenda-se alterar sua senha assim que possível.";
        $headers = "From: no-reply@gilvaniogringo.com\r\n";

        if (mail($destinatario, $assunto, $mensagem, $headers)) {
            echo "<p style='color:green'>Senha enviada para o e-mail cadastrado.</p>";
        } else {
            echo "<p style='color:red'>Erro ao enviar e-mail. Tente novamente mais tarde.</p>";
        }
    } else {
        echo "<p style='color:red'>Usuário não encontrado.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Intranet do Gringo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <form method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-sm">
        <h2 class="text-lg font-bold mb-4">Recuperar Senha</h2>
        <label for="usuario" class="block text-sm font-medium text-gray-700">Nome de usuário:</label>
        <input type="text" name="usuario" id="usuario" required class="w-full px-3 py-2 border rounded mt-1 mb-4">
        <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 w-full">Enviar senha por e-mail</button>
        <p class="text-center text-sm mt-4"><a href="intranet.html" class="text-blue-500 hover:underline">Voltar ao login</a></p>
    </form>
</body>
</html>
