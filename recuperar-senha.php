<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$usuario = $data['usuario'] ?? '';

$usuarios = [
  "graziele.albuquerque" => "albuquerquegraziele916@gmail.com",
  "gabriel.amaral" => "ggabi722@gmail.com"
];

// Verifica se o usuário existe
if (!isset($usuarios[$usuario])) {
  echo json_encode(["mensagem" => "Usuário não encontrado."]);
  exit;
}

$destinatario = $usuarios[$usuario];
$senha = "Gringo1975";

// Envio com SendGrid (ou use PHPMailer)
// Aqui com SendGrid:
$apikey = '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';
$de = "gilvaniagenda@gmail.com";

$mensagem = [
  "personalizations" => [[
    "to" => [["email" => $destinatario]],
    "subject" => "Recuperação de senha - Intranet do Gringo"
  ]],
  "from" => ["email" => $de, "name" => "Intranet do Gringo"],
  "content" => [[
    "type" => "text/plain",
    "value" => "Olá, {$usuario}. Sua senha de acesso é: {$senha}"
  ]]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mensagem));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $apikey",
  "Content-Type: application/json"
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode >= 200 && $httpcode < 300) {
  echo json_encode(["mensagem" => "Senha enviada para o e-mail cadastrado."]);
} else {
  echo json_encode(["mensagem" => "Erro ao enviar e-mail. Código: $httpcode"]);
}
