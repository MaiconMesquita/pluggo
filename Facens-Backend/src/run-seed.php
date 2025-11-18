<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

use App\Infra\Database\Fixtures\LoadFixtures;

$currentEnv = $_ENV['ENV'];
$isDev = in_array($_ENV['ENV'], ['dev', 'local']);

$loadFixtures = new LoadFixtures();

$seeds = $loadFixtures->loadFixtures(!$isDev);

if (count($seeds) === 0) {
    echo "nothing to do :) ";
    return;
}

foreach ($seeds as $seed) {
    echo "\n executing > " .  $seed::class . "\n";
    $seed->execute();
}

echo "\n all done :)";
