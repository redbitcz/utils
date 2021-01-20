<?php

declare(strict_types=1);

use Redbitcz\Utils\Log\Logger;
use Redbitcz\Utils\Writer\OutputWriter;

require_once __DIR__ . '/../../vendor/autoload.php';

$writer = new OutputWriter();
$outputLogger = new Logger($writer);

$outputLogger->info('This is info message.');
$outputLogger->debug('Here is {variable}', ['variable' => 'Hello World']);
