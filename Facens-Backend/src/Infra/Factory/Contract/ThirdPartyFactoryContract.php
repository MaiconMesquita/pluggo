<?php

namespace App\Infra\Factory\Contract;

use App\Helper\BrevoHelper;
use App\Infra\ThirdParty\ClientRequest\ClientRequest;
use App\Infra\ThirdParty\RequestValidator\RequestValidator;
use App\Infra\ThirdParty\Logging\Logging;
use App\Infra\ThirdParty\JWT\JWT;
use App\Infra\ThirdParty\Mail\Mail;
use App\Infra\ThirdParty\Queue\Queue;
use App\Infra\ThirdParty\Sms\Sms;
use App\Infra\ThirdParty\Storage\Storage;
use App\Infra\ThirdParty\InvoiceDateHelper\InvoiceDate;

interface ThirdPartyFactoryContract
{
    public function getClientRequest(): ClientRequest;
    public function getLogging(): Logging;
    public function getQueue(): Queue;
    public function getRequestValidator(): RequestValidator;
    public function getStorage(): Storage;
    public function getJWT(): JWT;
    public function getMail(): Mail;
    public function getSms(): Sms;
    public function getInvoiceDate(): InvoiceDate;
    public function getBrevoHelper(): BrevoHelper;
}
