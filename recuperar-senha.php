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
