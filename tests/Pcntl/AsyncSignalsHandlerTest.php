<?php
/**
 * @testCase
 * @phpExtension pcntl, posix
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Tests\Pcntl;

use Redbitcz\Utils\Pcntl\AsyncSignalsHandler;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class AsyncSignalsHandlerTest extends TestCase
{
    public function testSignalCatch(): void
    {
        AsyncSignalsHandler::register(SIGUSR1);
        AsyncSignalsHandler::register(SIGUSR2);

        posix_kill(posix_getpid(), SIGUSR1);

        Assert::true(AsyncSignalsHandler::isSignalReceived(SIGUSR1));
        Assert::false(AsyncSignalsHandler::isSignalReceived(SIGUSR2));
    }

    public function testSignalReset(): void
    {
        AsyncSignalsHandler::register(SIGUSR1);

        posix_kill(posix_getpid(), SIGUSR1);

        Assert::true(AsyncSignalsHandler::isSignalReceived(SIGUSR1));

        AsyncSignalsHandler::resetState(SIGUSR1);

        Assert::false(AsyncSignalsHandler::isSignalReceived(SIGUSR2));
    }
}

(new AsyncSignalsHandlerTest)->run();
