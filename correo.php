<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar los campos del formulario
    if (empty($_POST['name']) || empty($_POST['msg']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(500);
        exit();
    }

    // Obtener datos del formulario y sanearlos
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['msg']);

    // Configuración de PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.zoho.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cafecito@pinedodaniel.shop';
    $mail->Password = 'jaziulxd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configurar remitente y destinatario para Zoho
    $mail->setFrom('cafecito@pinedodaniel.shop', 'daniel    ');
    $mail->addAddress('cafecito@pinedodaniel.shop'); // Correo de Zoho

    // Configurar contenido del mensaje para Zoho
    $mail->isHTML(true);
    $mail->Subject = "Nuevo Mensaje de Contacto";
    $mail->Body = "Has recibido un nuevo mensaje desde el formulario de contacto de tu sitio web.<br><br>Detalles:<br><br>Nombre: $name<br>Email: $email<br>Mensaje: $message";



    try {
        // Enviar correo a Zoho
        $mail->send();

        // Enviar correo de agradecimiento al usuario
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = "Gracias por ponerte en contacto";
        $mail->Body = "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Agradecimiento</title>
            <style>
                body {
                    background-color: #e6f7ff;
                    text-align: center;
                }
                .container {
                    border: 2px solid #80bfff;
                    padding: 20px;
                    margin: 20px auto;
                    background-color: #ccf2ff;
                    max-width: 600px;
                    border-radius: 10px;
                }
                h1 {
                    color: #0066cc;
                }
                p {
                    color: #004080;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>¡Gracias por ponerte en contacto!</h1>
                <p>Hemos recibido tu mensaje <p><strong>Mensaje:</strong> $message</p>  y nos pondremos en contacto contigo pronto.</p>
                <p><em>Este es un mensaje de agradecimiento personalizado y profesional.</em></p>
            </div>
        </body>
        </html>";

        $mail->send();

        echo "Mensaje enviado correctamente";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error al enviar el mensaje";
        exit();
    }
} else {
    // Redirigir si no es una solicitud POST
    header("Location: contact.html");
    exit();
}
?>