<?php
/**
 * @testCase
 * @phpExtension pcntl, posix
 * @exitCode -1 // I have no idea why but it works
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Tests\Process;

use Redbitcz\Utils\Process\ProcessTerminationLock;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class ProcessTerminationUnlockTest extends TestCase
{
    public function testUnlock(): void
    {
        ProcessTerminationLock::setSignalTypes([SIGTERM]);
        ProcessTerminationLock::lock();
        ProcessTerminationLock::unlock(null, 10);

        posix_kill(posix_getpid(), SIGTERM);

        Assert::fail("Expected exited process on previous line");
    }

    public function testMissed(): void
    {
        ProcessTerminationLock::setSignalTypes([SIGTERM]);
        ProcessTerminationLock::lock();

        posix_kill(posix_getpid(), SIGINT);

        Assert::fail("Expected exited process on previous line");
    }

    public function testTwoSignalsAndUnlockFirstOne(): void
    {
        ProcessTerminationLock::setSignalTypes([SIGTERM, SIGINT]);
        ProcessTerminationLock::lock();
        ProcessTerminationLock::unlock(null, 10);

        posix_kill(posix_getpid(), SIGTERM);

        Assert::fail("Expected exited process on previous line");
    }

    public function testTwoSignalsAndUnlockSecondOne(): void
    {
        ProcessTerminationLock::setSignalTypes([SIGTERM, SIGINT]);
        ProcessTerminationLock::lock();
        ProcessTerminationLock::unlock(null, 10);

        posix_kill(posix_getpid(), SIGINT);

        Assert::fail("Expected exited process on previous line");
    }


    public function testTwoSignalsAndUnlockAnotherOne(): void
    {
        ProcessTerminationLock::setSignalTypes([SIGTERM, SIGHUP]);
        ProcessTerminationLock::lock();
        ProcessTerminationLock::unlock(null, 10);

        posix_kill(posix_getpid(), SIGINT);

        Assert::fail("Expected exited process on previous line");
    }
}

(new ProcessTerminationUnlockTest)->run();
