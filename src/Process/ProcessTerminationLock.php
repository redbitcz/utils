<?php
/** @noinspection PhpComposerExtensionStubsInspection (not required because Windows) */

declare(strict_types=1);

namespace Redbitcz\Utils\Process;

use Redbitcz\Utils\Pcntl\AsyncSignalsHandler;

/**
 * SigIntLock provide lock to Unix-based process SIGTERM signal to prevent process unwanted termination
 *
 * When no PCNTL extension available in PHP, all methods do nothing
 */
class ProcessTerminationLock
{
    /** @var int */
    private static $signo = SIGTERM;

    /**
     * Hold Unix-based process SIGTERM signal
     *
     * @param callable|null $callback Optional. Called when signal received
     */
    public static function lock(?callable $callback = null): void
    {
        AsyncSignalsHandler::register(self::$signo, $callback);
    }

    /**
     * Release Unix-based process SIGTERM signal
     *
     * When the SIGTERM sinal received during locked state, method exits the process now
     *
     * @param callable|null $callback Optional. Called when process exiting by holded signal
     * @param int $exitCode Optional. Exit code when process exiting by holded signal
     */
    public static function unlock(?callable $callback = null, int $exitCode = 0): void
    {
        AsyncSignalsHandler::deregister(self::$signo);

        // Chech if exit signal received during lock, then exit process now
        if (AsyncSignalsHandler::isSignalReceived(self::$signo)) {
            if (is_callable($callback)) {
                $callback();
            }

            exit($exitCode);
        }
    }

    /**
     * Set type of watched termination signal (default: SIGTERM)
     *
     * @param int $signo
     */
    public static function setSignalType(int $signo): void
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
