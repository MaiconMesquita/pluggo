<?php

namespace App\Infra\ThirdParty\Mail;

use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerAdapter implements Mail
{
    public function __construct(private PHPMailer $mail) {}

    public function sendSampleMail(array $to, string $subject, string $message, bool $isHTML = false): bool
    {
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPDebug = 0;
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = $_ENV['MAIL_PORT'];
        $this->mail->setFrom($_ENV['MAIL_FROM']);

        foreach ($to as $value) {
            $this->mail->addAddress($value);
        }
        $this->mail->isHTML($isHTML);
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;

        return $this->mail->send();
    }
}
