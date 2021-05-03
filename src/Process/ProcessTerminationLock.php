<?php
/** @noinspection PhpComposerExtensionStubsInspection (not required because Windows) */

declare(strict_types=1);

namespace Redbitcz\Utils\Process;

use Redbitcz\Utils\Pcntl\AsyncSignalsHandler;
use Redbitcz\Utils\Pcntl\Signals;

/**
 * ProcessTerminationLock provide lock to Unix-based process SIGTERM signal to prevent process unwanted termination
 *
 * SIGTERM signal can be changed to anothers through `setSignalTypes()` method
 *
 * When no PCNTL extension available in PHP, all methods do nothing
 */
class ProcessTerminationLock
{
    /**
     * @var int[]
     * @see \Redbitcz\Utils\Pcntl\Signals constants
     */
    private static $signo = [Signals::SIGTERM];

    /**
     * Hold Unix-based process SIGTERM signal (or others defined through `setSignalTypes()` method)
     *
     * @param callable|null $callback Optional. Called when signal received
     */
    public static function lock(?callable $callback = null): void
    {
        foreach (self::$signo as $signo) {
            AsyncSignalsHandler::register($signo, $callback);
        }
    }

    /**
     * Release Unix-based process SIGTERM signal  (or others defined through `setSignalTypes()` method)
     *
     * When the SIGTERM sinal received during locked state, method exits the process now
     *
     * @param callable|null $callback Optional. Called when process exiting by holded signal
     * @param int $exitCode Optional. Exit code when process exiting by holded signal
     */
    public static function unlock(?callable $callback = null, int $exitCode = 0): void
    {
        foreach (self::$signo as $signo) {
            AsyncSignalsHandler::deregister($signo);

            // Chech if exit signal received during lock, then exit process now
            if (AsyncSignalsHandler::isSignalReceived($signo)) {
                if (is_callable($callback)) {
                    $callback();
                }

                exit($exitCode);
            }
        }
    }

    /**
     * Set types of watched termination signal (default: SIGTERM)
     * @param int[] $signo
     *
     * @see \Redbitcz\Utils\Pcntl\Signals constants
     */
    public static function setSignalTypes(array $signo): void
    {
        self::$signo = $signo;
    }

    /**
     * Returns `true` when PCNTL extension available in PHP
     *
     * @return bool
     */
    public static function isAvailable(): bool
    {
        return AsyncSignalsHandler::isAvailable();
    }
}
