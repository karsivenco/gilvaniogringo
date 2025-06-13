<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os dados do formulário
    $name = htmlspecialchars($_POST['name']);
    $bairro = htmlspecialchars($_POST['bairro']);
    $phone = htmlspecialchars($_POST['phone']);
    $dob = htmlspecialchars($_POST['dob']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Configurações do e-mail
    $to = 'seu_email@exemplo.com'; // Altere para seu e-mail
    $subjectEmail = "Nova mensagem de contato: $subject";
    $body = "Nome: $name\nBairro: $bairro\nTelefone: $phone\nData de Nascimento: $dob\nAssunto: $subject\nMensagem: $message";
    $headers = "From: no-reply@seusite.com\r\n"; // Altere para um e-mail que você controla

    // Envia o e-mail
    if (mail($to, $subjectEmail, $body, $headers)) {
        echo "Mensagem enviada com sucesso!";
    } else {
        echo "Erro ao enviar a mensagem.";
    }
}
?>
