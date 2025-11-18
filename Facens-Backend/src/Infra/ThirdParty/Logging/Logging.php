<?php

namespace App\Infra\ThirdParty\Logging;

interface Logging {
    public function debug(string $message);
    public function info(string $message);
    public function warning(string $message);
    public function error(string $message);
}
