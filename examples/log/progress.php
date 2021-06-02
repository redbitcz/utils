<?php

declare(strict_types=1);

use Redbitcz\Utils\IO\OutputWriter;
use Redbitcz\Utils\Log\Logger;
use Redbitcz\Utils\Log\Progress;

require_once __DIR__ . '/../../vendor/autoload.php';

$writer = new OutputWriter();
$outputLogger = new Logger($writer);
$progress = new Progress($outputLogger, 5);

$outputLogger->info('Start progress');
for ($i = 1; $i <= 5; $i++) {
    $progress->step('Proces #' . $i);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Create a box');
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Take the paper', null, 2);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Find the paper in the closet', null, 3);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Take the paper in your hand', null, 3);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Put the paper on the table', null, 3);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Fold the paper into a box', null, 2);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Stick the box with duct tape', null, 2);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Take the duct tape', null, 3);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Stick it on the joints of the box', null, 3);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Put the finished box on the table', null, 2);
    usleep(random_int(10000, 100000));
    $progress->stepInStep('Put anything in the box');
    usleep(random_int(10000, 100000));
}
$outputLogger->info('End progress');
