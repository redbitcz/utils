<?php
/**
 * @testCase
 * @phpExtension pcntl, posix
 * @noinspection PhpComposerExtensionStubsInspection
 */

declare(strict_types=1);

namespace Tests\Pcntl;

use Redbitcz\Utils\Pcntl\Signals;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

class SignalsTest extends TestCase
{
    public function getSignals(): array
    {
        return [
            [Signals::SIGHUP, 'SIGHUP'],
            [Signals::SIGINT, 'SIGINT'],
            [Signals::SIGQUIT, 'SIGQUIT'],
            [Signals::SIGILL, 'SIGILL'],
            [Signals::SIGTRAP, 'SIGTRAP'],
            [Signals::SIGABRT, 'SIGABRT'],
            [Signals::SIGIOT, 'SIGIOT'],
            [Signals::SIGFPE, 'SIGFPE'],
            [Signals::SIGKILL, 'SIGKILL'],
            [Signals::SIGSEGV, 'SIGSEGV'],
            [Signals::SIGPIPE, 'SIGPIPE'],
            [Signals::SIGALRM, 'SIGALRM'],
            [Signals::SIGTERM, 'SIGTERM'],
        ];
    }

    /**
     * @dataProvider getSignals
     * @param int $customSignalValue
     * @param string $signalConstantName
     */
    public function testInternalSignalsEquality(int $customSignalValue, string $signalConstantName): void
    {
        Assert::true(defined($signalConstantName), "{$signalConstantName} signal constant exists");

        Assert::same(constant($signalConstantName), $customSignalValue, "{$signalConstantName} signal");
    }
}

(new SignalsTest)->run();
