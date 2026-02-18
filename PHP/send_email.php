<?php
function sendVerificationEmail($email, $nombre, $token) {
    $subject = "TechHardware - Código de Verificación";
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; }
            .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 10px; }
            .header { background: #00ff41; color: #0a0e27; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { padding: 30px; }
            .code { background: #0a0e27; color: #00ff41; font-size: 32px; font-weight: bold; padding: 20px; text-align: center; border-radius: 5px; letter-spacing: 5px; }
            .footer { text-align: center; margin-top: 30px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>TechHardware</h1>
            </div>
            <div class='content'>
                <h2>¡Hola {$nombre}!</h2>
                <p>Gracias por registrarte en TechHardware. Para completar tu registro, ingresa el siguiente código de verificación:</p>
                <div class='code'>{$token}</div>
                <p>Este código expira en 15 minutos.</p>
                <p>Si no solicitaste este registro, ignora este email.</p>
            </div>
            <div class='footer'>
                <p>© 2025 TechHardware - Ferretería Digital</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: TechHardware <noreply@techhardware.com>" . "\r\n";
    
    return mail($email, $subject, $message, $headers);
}
?>