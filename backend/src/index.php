<?php
// @codeCoverageIgnoreStart

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Credentials: true"); // allow credentiais
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE"); // Allow the HTTP methods you need
header("Access-Control-Allow-Headers: Content-Type, Authorization, Api-Key, Access-Control-Allow-Origin, Recaptcha"); // Allow specific headers

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/bootstrap.php';
require __DIR__ . '/Routes.php';

// @codeCoverageIgnoreEnd
