<?php
namespace Classes;
use PHPMailer\PHPMailer\PHPMailer;

class Email {
    private $email;
    private $name;
    private $token;

    public function __construct($email, $name, $token) {
        $this->email = $email;
        $this->name = $name;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'c70fb311507726';
        $phpmailer->Password = '89c710ac997bde';

        $phpmailer->setFrom('cuentas@appsalon.com');
        $phpmailer->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $phpmailer->Subject = 'Confirma tu cuenta';

        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';

        $content = "<html>";
        $content.="<p><strong>Hola " . $this->name . "</strong></p>";
        $content.="<p>Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace: </p>";
        $content.="<a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "' >Confirmar cuenta</a>";
        $content.="<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje.</p>";
        $content.= "</html>";
        $phpmailer->Body = $content;
        $phpmailer->send();
    }
}