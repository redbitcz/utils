<?php

declare(strict_types=1);

use Redbitcz\Utils\Log\Logger;
use Redbitcz\Utils\Log\Progress;
use Redbitcz\Utils\Writer\OutputWriter;

require_once __DIR__ . '/../../vendor/autoload.php';

$writer = new OutputWriter();
$outputLogger = new Logger($writer);
$progress = new Progress($outputLogger, 5);

$outputLogger->info('Start progress');
for ($i = 0; $i < 5; $i++) {
    usleep(random_int(100000, 1000000));
    $progress->step('This is one step #' . $i);
}
$outputLogger->info('End progress');
