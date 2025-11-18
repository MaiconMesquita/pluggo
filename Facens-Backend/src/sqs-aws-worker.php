<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

use App\Application\Handler\Handler as ApplicationHandler;
use App\Domain\Entity\Request;
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;
use App\Infra\Factory\{SqsHandleFactory, ThirdPartyFactory};
use App\Infra\ThirdParty\Logging\{BrefLoggingAdapter, Logging};

use Bref\Context\Context;
use Bref\Event\Sqs\{SqsEvent, SqsHandler};

use Aws\Exception\AwsException;


class Handler extends SqsHandler
{

    public function __construct(
        private ThirdPartyFactoryContract $thirdParty,
        private Logging                   $logging
    ) {
        
    }

    private function execute(ApplicationHandler $applicationHandler, $body, Context $context)
    {
        try {
            $applicationHandler->handle();
        } catch (Exception | AwsException | ErrorException | RuntimeException | Throwable $e) {
            $message = "- Mensagem: " . $e->getMessage()
                . "\n- Tipo de exception: " . $e::class
                . "\n- Body: " . json_encode($body)
                . "\n- Stacktrace: \n" . json_encode($e->getTrace());
            $request = new Request(
                new DateTime(),
                $context->getAwsRequestId(),
                '-- VAZIO --'
            );            
            $this->logging->info($message);
        }
    }

    public function handleSqs(SqsEvent $event, Context $context): void
    {
        foreach ($event->getRecords() as $record) {
            $body = json_decode($record->getBody(), true);
            $applicationHandler = SqsHandleFactory::getHandler($body['eventName'], $body);
            $this->execute($applicationHandler, $body, $context);
        }
    }
}

return new Handler(
    new ThirdPartyFactory(),
    new BrefLoggingAdapter(
        new \Bref\Logger\StderrLogger(
            \Psr\Log\LogLevel::DEBUG
        )
    )
);
