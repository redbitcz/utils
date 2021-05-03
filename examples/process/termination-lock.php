<?php

declare(strict_types=1);

use Redbitcz\Utils\Pcntl\Signals;
use Redbitcz\Utils\Process\ProcessTerminationLock;

require_once __DIR__ . '/../../vendor/autoload.php';

if (ProcessTerminationLock::isAvailable() === false) {
    echo "WARNING: PHP doesn't implemented the PCNTL extensions, signal will be handled by system default handler! ";
}

/**
 * Watch to SIGINT signal for test with Ctrl-C, but default termination by SIGTERM
 */
ProcessTerminationLock::setSignalTypes([Signals::SIGINT]);

function announceSignal(): void
{
    echo " [Registered SIGINT signal] ";
}

function announceExit(): void
{
    echo " [Exiting by holded signal]\n";
}

while (true) {
    ProcessTerminationLock::lock('announceSignal');
    echo "\nLocked:   ";

    for ($i = 0; $i < 50; $i++) {
        echo ".";
        usleep(100000);
    }

    ProcessTerminationLock::unlock('announceExit');
    echo "\nUnlocked: ";

    for ($i = 0; $i < 50; $i++) {
        echo ".";
        usleep(100000);
    }
}
