<?php

namespace App\Domain\Entity\Service\BrevoService;

class BrevoEmailInput
{
    public string $senderName;
    public string $senderEmail;
    public string $toName;
    public string $toEmail;
    public string $subject;
    public string $htmlContent;

    public function __construct(
        string $toName,
        string $toEmail,
        string $subject,
        string $htmlContent,
        ?string $senderName = null,
        ?string $senderEmail = null
    ) {
        // Se não forem passados, pega do .env
        $this->senderName = $senderName ?? ($_ENV['BREVO_SENDER_NAME'] ?? 'PlugGo Oficial');
        $this->senderEmail = $senderEmail ?? ($_ENV['BREVO_SENDER_EMAIL'] ?? 'pluggoofficial@outlook.com');
        $this->toName = $toName;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
    }

    public function toArray(): array
    {
        return [
            "sender" => [
                "name" => $this->senderName,
                "email" => $this->senderEmail,
            ],
            "to" => [
                [
                    "email" => $this->toEmail,
                    "name" => $this->toName,
                ],
            ],
            "subject" => $this->subject,
            "htmlContent" => $this->htmlContent,
        ];
    }
}
