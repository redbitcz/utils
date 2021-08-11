<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Redbitcz\Utils\IO\OutputWriter;
use Redbitcz\Utils\Log\Logger;

require_once __DIR__ . '/../../vendor/autoload.php';

$writer = new OutputWriter();
$logger = new Logger($writer);

$logger->debug('This is Logger demo:');
$logger->info('Here is {variable}', ['variable' => 'Hello World']);

$logger->debug('This is SectionLogger demo:');

$messages = ['foo', 'bar', 'baz', 'foo bar'];
$logger->info('Prepared {count} messages to process', ['count' => count($messages)]);

foreach ($messages as $messageKey => $message) {
    processMessage($message, $logger->section("Message ID $messageKey"));
}

function processMessage(string $message, LoggerInterface $logger)
{
    $logger->info('Processing message with text: "{message}"', ['message' => $message]);

    $message = strrev($message);
    $logger->info('OK');

    $logger->info('Procesed mesage is: "{message}"', ['message' => $message]);
}
