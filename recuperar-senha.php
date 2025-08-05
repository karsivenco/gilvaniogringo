<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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
        $emailDestino = $usuarios[$usuario]["email"];
        $senhaUsuario = $usuarios[$usuario]["senha"];

        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gilvaniagenda@gmail.com'; // SEU E-MAIL
            $mail->Password = 'SENHA_DE_APLICATIVO';     // SENHA DE APP (não a senha comum)
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remetente
            $mail->setFrom('gilvaniagenda@gmail.com', 'Intranet do Gringo');
            // Destinatário
            $mail->addAddress($emailDestino);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - Intranet do Gringo';
            $mail->Body = "Olá <b>$usuario</b>,<br><br>Sua senha de acesso é: <b>$senhaUsuario</b><br><br>Recomenda-se alterá-la após o login.";

            $mail->send();
            echo "<script>alert('Senha enviada com sucesso para o e-mail cadastrado.'); window.location.href='intranet.html';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Erro ao enviar o e-mail. Detalhes: {$mail->ErrorInfo}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado.'); window.history.back();</script>";
    }
}
?>
