<?php
    namespace App\Libraries;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class PHPMailerService {
        protected $mail;

        public function __construct()
        {
            $this->mail = new PHPMailer(true);

            // Configuración de SMTP
            $this->mail->isSMTP();
            $this->mail->SMTPAuth = true;
            $this->mail->Host = "smtp-relay.brevo.com";
            $this->mail->Username = "7deb48001@smtp-brevo.com";
            $this->mail->Password = "pNJOEyfn3XwPqrQx";
            $this->mail->Port = 587;
            $this->mail->SMTPSecure = 'tls';
        }

        // Función que configura los parámetros del correo, como el remitente, destinatario..., utilizando PHPMailer.
        public function sendEmail($to, $subject, $body, $from = "saidfcb2@gmail.com", $fromName = "Aplicacion web Trenes") {
            try {
                $this->mail->setFrom($from, $fromName);
                $this->mail->addAddress($to);

                $this->mail->isHTML(true);
                $this->mail->Subject = $subject;
                $this->mail->Body = $body;

                $this->mail->send();
                return true;
            } catch (Exception $e) {
                log_message('error', 'Error al enviar correo: ' . $this->mail->ErrorInfo);
                return false;
            }
        }
    }

?>