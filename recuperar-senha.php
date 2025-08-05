<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuarios = [
        "graziele.albuquerque" => [
            "email" => "albuquerquegraziele916@gmail.com",
            "senha" => "Gringo1975"
        ],
        "gabriel.amaral" => [
            "email" => "ggabi722@gmail.com",
            "senha" => "Gringo1975"
        ]
    ];

    $usuario = trim($_POST["usuario"]);

    if (isset($usuarios[$usuario])) {
        $destinatario = $usuarios[$usuario]["email"];
        $senha = $usuarios[$usuario]["senha"];
        $assunto = "Recuperação de Senha - Intranet do Gringo";
        $mensagem = "Olá $usuario,\n\nConforme solicitado, aqui está sua senha atual para acesso à Intranet do Gringo:\n\nSenha: $senha\n\nRecomenda-se alterá-la após o login.";
        $headers = "From: no-reply@gilvaniogringo.com\r\n";

        if (mail($destinatario, $assunto, $mensagem, $headers)) {
            echo "<script>alert('Senha enviada para o e-mail cadastrado!'); window.location.href='intranet.html';</script>";
        } else {
            echo "<script>alert('Erro ao enviar o e-mail. Tente novamente.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado.'); window.history.back();</script>";
    }
}
?>
