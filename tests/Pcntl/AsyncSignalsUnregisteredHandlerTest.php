<?php
/**
 * @testCase
 * @phpExtension pcntl, posix
 * @exitCode -1
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Tests\Pcntl;

use Redbitcz\Utils\Pcntl\AsyncSignalsHandler;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class AsyncSignalsUnregisteredHandlerTest extends TestCase
{
    public function testUnregistered(): void
    {
        posix_kill(posix_getpid(), SIGUSR1);
        // Only checks same exitCode - see the TesCase annotation
    }
    public function testDeregistered(): void
    {
        AsyncSignalsHandler::register(SIGUSR1);
        AsyncSignalsHandler::deregister(SIGUSR1);
        posix_kill(posix_getpid(), SIGUSR1);
        Assert::false(AsyncSignalsHandler::isSignalReceived(SIGUSR1));
    }
}

(new AsyncSignalsUnregisteredHandlerTest)->run();
