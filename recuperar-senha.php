<?php
session_start();

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
        $destinatario = $usuarios[$usuario]["email"];
        $senha = $usuarios[$usuario]["senha"];

        $mail = new PHPMailer(true);
        try {
            // Configurações SMTP Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gilvaniagenda@gmail.com';       // SEU E-MAIL
            $mail->Password = 'SUA_SENHA_DE_APLICATIVO';        // SENHA DE APLICATIVO
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remetente e destinatário
            $mail->setFrom('gilvaniagenda@gmail.com', 'Intranet do Gringo');
            $mail->addAddress($destinatario);

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - Intranet do Gringo';
            $mail->Body = "
                <p>Olá <b>$usuario</b>,</p>
                <p>Conforme solicitado, aqui está sua senha atual para acesso à Intranet do Gringo:</p>
                <p><b>Senha: $senha</b></p>
                <p>Recomenda-se alterá-la após o login.</p>
            ";

            $mail->send();

            $_SESSION['msg_sucesso'] = "Senha enviada com sucesso para o e-mail cadastrado!";
            header("Location: recuperar-senha.html");
            exit;

        } catch (Exception $e) {
            $_SESSION['msg_erro'] = "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
            header("Location: recuperar-senha.html");
            exit;
        }
    } else {
        $_SESSION['msg_erro'] = "Usuário não encontrado.";
        header("Location: recuperar-senha.html");
        exit;
    }
} else {
    header("Location: recuperar-senha.html");
    exit;
}
