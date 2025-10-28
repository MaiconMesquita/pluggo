<?php

namespace App\Infra\ThirdParty\Queue;

use App\Domain\Event\DomainEvent;
use App\Infra\Factory\SqsHandleFactory;
use Aws\Exception\AwsException;
use Aws\Sqs\Exception\SqsException;
use Exception;

class AwsSqsAdapter implements Queue
{

    public function __construct(private $sqsClient)
    {
    }

    public function connect()
    {
        if (!in_array($_ENV['ENV'], ['local', 'dev'])) return;

        $this->sqsClient->createQueue(
            [
                'QueueName' => "local-queue",
            ]
        );
    }

    public function close()
    {
    }

    public function consume()
    {

        if (!in_array($_ENV['ENV'], ['local', 'dev'])) return;

        $client = $this->sqsClient;

        $queueUrl = $_ENV['QUEUE_URL'];

        try {
            $result = $client->receiveMessage([
                'QueueUrl'            => $queueUrl,
                'MaxNumberOfMessages' => 10,
                'WaitTimeSeconds'     => 20,
            ]);

            if (!empty($result->get('Messages'))) {
                foreach ($result->get('Messages') as $message) {
                    // Processar a mensagem
                    echo "Mensagem: " . $message['Body'] . "\n";

                    $body = json_decode($message["Body"], true);

                    $eventName = $body["eventName"];                   

                    $handler = SqsHandleFactory::getHandler($eventName, $body);

                    $handler->handle();

                    // Remover a mensagem da fila após o processamento, se necessário
                    $client->deleteMessage([
                        'QueueUrl'      => $queueUrl,
                        'ReceiptHandle' => $message['ReceiptHandle'],
                    ]);
                }
            } else {
                echo "Nenhuma mensagem encontrada na fila.\n";
            }
        } catch (AwsException $e) {
            // Lidar com erros aqui
            echo "Erro: " . $e->getMessage() . "\n";
        }
    }

    public function publish(DomainEvent $domainEvent)
    {
        if ($_ENV['ENV'] === 'local') return;
        $params = [
            'MessageBody' => json_encode([
                'eventName' => $domainEvent->getName(),
                'payload' => $domainEvent->getPayload(),
                'token' => $_ENV['QUEUE_TOKEN']
            ]), 
            'QueueUrl' => $_ENV['QUEUE_URL_FIFO'],
            'MessageDeduplicationId' => uniqid(),
            'MessageGroupId' => $domainEvent->getName()
        ];

        try {
            $this->sqsClient->sendMessage($params);
        } catch (SqsException $sq) {
            throw new Exception($sq->getMessage(), 1);
        }
    }
}
