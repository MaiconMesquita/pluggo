<?php

namespace App\Infra\ThirdParty\Logging;

use Bref\Logger\StderrLogger;

class BrefLoggingAdapter implements Logging {


    public function __construct(private StderrLogger $logger)
    {}


    public function debug(string $message)
    {
        $this->logger->debug($message);
    }

    public function info(string $message)
    {
        $this->logger->info($message);
    }

    public function warning(string $message)
    {
        $this->logger->warning($message);
    }

    public function error(string $message)
    {
        $this->logger->error($message);
    }

}
