<?php
/** @noinspection PhpComposerExtensionStubsInspection (because Windows) */

declare(strict_types=1);

use Redbitcz\Utils\Pcntl\AsyncSignalsHandler;
use Redbitcz\Utils\Pcntl\Signals;

require_once __DIR__ . '/../../vendor/autoload.php';

function announceReceivedSignal(): void
{
    echo " [Registered SIGINT signal] ";
}

if (AsyncSignalsHandler::isAvailable() === false) {
    echo "WARNING: PHP doesn't implemented the PCNTL extensions, signal will be handled by system default handler! ";
}

AsyncSignalsHandler::register(Signals::SIGINT, 'announceReceivedSignal');

while (true) {
    echo ".";
    sleep(2);

    // Note: This examples exits immediately after signal receved because signal breaks the pause of the asleep process
    if (AsyncSignalsHandler::isSignalReceived(Signals::SIGINT)) {
        echo " Exiting because request!";
        exit;
    }
}

