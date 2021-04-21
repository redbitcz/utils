<?php
/**
 * @testCase
 * @phpExtension pcntl, posix
 * @exitCode 10
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Tests\Process;

use Redbitcz\Utils\Process\ProcessTerminationLock;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class ProcessTerminationLockTest extends TestCase
{
    public function testLock(): void
    {
        ProcessTerminationLock::setSignalType(SIGUSR1);
        ProcessTerminationLock::lock();

        posix_kill(posix_getpid(), SIGUSR1);

        ProcessTerminationLock::unlock(null, 10);
    }
}

(new ProcessTerminationLockTest)->run();
