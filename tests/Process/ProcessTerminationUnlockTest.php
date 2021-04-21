<?php
/**
 * @testCase
 * @phpExtension pcntl, posix
 * @exitCode -1
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
        ProcessTerminationLock::setSignalType(SIGTERM);
        ProcessTerminationLock::lock();
        ProcessTerminationLock::unlock(null, 10);

        posix_kill(posix_getpid(), SIGTERM);

        Assert::fail("Expected exited process on previous line");
    }
}

(new ProcessTerminationUnlockTest)->run();
