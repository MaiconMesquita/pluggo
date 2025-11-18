<?php

namespace App\Infra\Factory;

use App\Helper\BrevoHelper;
use App\Helper\ConnectifyServiceHelper;
use Aws\Sqs\SqsClient;
use Rakit\Validation\Validator;
use GuzzleHttp\Client as GuzzleClient;
use Aws\Credentials\{CredentialProvider, Credentials};
use App\Infra\ThirdParty\Sms\Sms;
use App\Infra\ThirdParty\Sms\ComTeleSmsAdapter;
use App\Infra\Database\Doctrine;
use App\Infra\Repository\SmsHistoryRepository;
use App\Infra\ThirdParty\Queue\{AwsSqsAdapter, Queue};
use App\Infra\Factory\Contract\ThirdPartyFactoryContract;
use App\Infra\ThirdParty\Uuid\RamseyUuidAdapter;
use App\Infra\ThirdParty\JWT\{JWT, JWTAdapter};
use App\Infra\ThirdParty\Logging\{BrefLoggingAdapter, Logging};
use App\Infra\ThirdParty\ClientRequest\{ClientRequest, GuzzleHttpAdapter};
use App\Infra\ThirdParty\Mail\Mail;
use App\Infra\ThirdParty\Mail\PHPMailerAdapter;
use App\Infra\ThirdParty\RequestValidator\{RakitValidatorAdapter, RequestValidator};
use App\Infra\ThirdParty\Storage\AwsS3Adapter;
use App\Infra\ThirdParty\Storage\Storage;
use Aws\S3\S3Client;
use PHPMailer\PHPMailer\PHPMailer;
use App\Infra\ThirdParty\InvoiceDateHelper\InvoiceDate;
use App\Infra\ThirdParty\InvoiceDateHelper\InvoiceDateAdapter;

/**
 * @codeCoverageIgnore
 */

class ThirdPartyFactory implements ThirdPartyFactoryContract
{
    private ?Queue            $queue = null;
    private ?Logging          $logging = null;
    private ?ClientRequest    $clientRequest = null;
    private ?RequestValidator $requestValidator = null;
    private ?JWT              $jwt = null;
    private ?Mail             $mail = null;
    private ?Sms              $sms          = null;
    private ?Storage          $storage       = null;
    private ?InvoiceDate $invoiceDate = null;
    private ?BrevoHelper $brevoHelper = null;


    public function getQueue(): Queue
    {
        if (!$this->queue) {
            $params = [
                'region' => 'sa-east-1',
                'version' => '2012-11-05'
            ];

            if (in_array($_ENV['ENV'], ['local']))
                $params  = array_merge($params, [
                    'endpoint' => "http://localstack_brandscard:4566",
                    'credentials' => [
                        "secret"  => "000000000000",
                        "key"     => "foo",
                    ]
                ]);
            else
                $params['credentials'] = CredentialProvider::defaultProvider();

            $this->queue = new AwsSqsAdapter(new SqsClient($params));
        }
        return $this->queue;
    }

    public function getLogging(): Logging
    {
        if (!$this->logging) {
            $this->logging = new BrefLoggingAdapter(
                new \Bref\Logger\StderrLogger(\Psr\Log\LogLevel::DEBUG)
            );
        }
        return $this->logging;
    }

    public function getClientRequest(): ClientRequest
    {
        if (!$this->clientRequest)
            $this->clientRequest = new GuzzleHttpAdapter(
                new GuzzleClient()
            );
        return $this->clientRequest;
    }
    public function getRequestValidator(): RequestValidator
    {
        if (!$this->requestValidator)
            $this->requestValidator = new RakitValidatorAdapter(
                new Validator()
            );
        return $this->requestValidator;
    }

    public function getBrevoHelper(): BrevoHelper
{
    if (!$this->brevoHelper) {
        $this->brevoHelper = new BrevoHelper(
            $this->getClientRequest(),
            $this->getLogging()
        );
    }

    return $this->brevoHelper;
}

    public function getJWT(): JWT
    {
        if (!$this->jwt) $this->jwt = new JWTAdapter(new RamseyUuidAdapter());
        return $this->jwt;
    }

    public function getSms(): Sms
    {
        if (!$this->sms)
            $this->sms = new ComTeleSmsAdapter(
                $this->getClientRequest(),
                new SmsHistoryRepository(Doctrine::getInstance()->getEntityManager()),
            );

        return $this->sms;
    }

    public function getStorage(): Storage
    {
        if (!$this->storage) {

            if (in_array($_ENV['ENV'], ['local'])) {
                $credentials = [
                    'endpoint' => is_file('/.dockerenv') ? 'http://localstack_brandscard:4566' : 'http://localhost:4566',
                    'use_path_style_endpoint' => true,
                    'credentials' => new Credentials(
                        'foo',
                        'baar'
                    ),
                ];
            } else {
                $credentials = ['credentials' => CredentialProvider::defaultProvider()];
            }

            $client = new S3Client(
                array_merge(
                    [
                        'region' => 'sa-east-1',
                        'version' => 'latest',
                    ],
                    $credentials
                )
            );

            $this->storage = new AwsS3Adapter($client);
        }

        return $this->storage;
    }
    public function getMail(): Mail
    {
        if (! $this->mail) {
            $this->mail = new PHPMailerAdapter(new PHPMailer());
        }

        return $this->mail;
    }

    public function getInvoiceDate(): InvoiceDate
    {
        if (!$this->invoiceDate) {
            $this->invoiceDate = new InvoiceDateAdapter();
        }

        return $this->invoiceDate;
    }
}
