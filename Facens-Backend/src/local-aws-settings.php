<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap.php';

if (!in_array($_ENV['ENV'], ['local']))
    return;

use App\Infra\Factory\ThirdPartyFactory;


$thirdPartyFactory = new ThirdPartyFactory();

$s3 = $thirdPartyFactory->getStorage();
try {
    $s3->createBucket();
} catch (\Throwable $th) {
    //throw $th;
}

$queue = $thirdPartyFactory->getQueue();
$queue->connect();

echo "iniciando leitura \n";

while (true) {
    $queue->consume();
    echo "reiniciando \n";
    sleep(10);
}
