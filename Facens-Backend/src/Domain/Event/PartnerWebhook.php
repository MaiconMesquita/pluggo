<?php
namespace App\Domain\Event;

use App\Domain\Entity\Webhook;


class PartnerWebhook implements DomainEvent {

    private string $eventName = 'partnerWebhook';

    public function __construct(private Webhook $webhook, private string $idempotencyKey)
    {}

    public function getName(): string
    {
        return $this->eventName;
    }

    public function getWebhook(): Webhook
    {
        return $this->webhook;
    }

    public function getPayload(): array
    {
        return [
            'type' => $this->webhook->getType(),
            'body' => $this->webhook->getBody(),
            'idempotencyKey' => $this->idempotencyKey
        ];
    }

    public function getDelaySeconds(): int
    {
        return 0;
    }

    public function getIdempotencyKey(): ?string
    {
        return $this->idempotencyKey;
    }
}
