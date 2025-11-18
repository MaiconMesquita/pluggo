<?php

use App\Infra\ThirdParty\Env\PhpDotEnvAdapter;
use App\Infra\ThirdParty\Logging\BrefLoggingAdapter;

PhpDotEnvAdapter::load(__DIR__ . '/..');
ini_set('display_errors', (int)$_ENV['DEBUG']);

$logger = new BrefLoggingAdapter(new \Bref\Logger\StderrLogger(\Psr\Log\LogLevel::DEBUG));
if (!empty($_SERVER['REQUEST_METHOD'])) $logger->info('[method]: ' . $_SERVER['REQUEST_METHOD']);
if (!empty($_SERVER['REQUEST_URI'])) $logger->info('[route]: ' . $_SERVER['REQUEST_URI']);
$logger->info('[headers]: ' . json_encode(getallheaders()));
$logger->info('[body]: ' . file_get_contents('php://input'));

