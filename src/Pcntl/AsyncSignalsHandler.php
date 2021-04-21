<?php
/** @noinspection PhpComposerExtensionStubsInspection */

declare(strict_types=1);

namespace Redbitcz\Utils\Pcntl;

class AsyncSignalsHandler
{
    /** @var array<int, bool> */
    private static $signalsState = [];

    /** @var array<int, callable> */
    private static $callbacks = [];

    /**
     * Register the handler of Unix-based process signal.
     *
     * When no PCNTL extension available in PHP, the method do nothing
     *
     * @param int $signo The signal number, see `pcntl_signal()`
     * @param callable|null $callback Optional. Addidional callback, called when signal received
     */
    public static function register(int $signo, ?callable $callback = null): void
    {
        if (self::isAvailable() === false) {
            return;
        }

        // Closure to access private method
        $handler = static function () use ($signo) {
            self::handleSignal($signo);
        };

        self::$callbacks[$signo] = $callback;
        self::resetState($signo);

        pcntl_async_signals(true);
        pcntl_signal($signo, $handler);
    }

    /**
     * Register the handler of Unix-based process signal. Returns the default system handler of signals.
     *
     * When no PCNTL extension available in PHP, the method do nothing
     *
     * @param int $signo The signal number, see `pcntl_signal()`
     */
    public static function deregister(int $signo): void
    {
        if (self::isAvailable() === false) {
            return;
        }

        pcntl_signal($signo, SIG_DFL);

        unset(self::$callbacks[$signo]);
    }

    /**
     * Returns `true` when the handled signal was received from the moment the hadler is registered or state resetted
     *
     * @param int $signo The signal number, see `pcntl_signal()`
     * @return bool
     */
    public static function isSignalReceived(int $signo): bool
    {
        return self::$signalsState[$signo] ?? false;
    }

    /**
     * Returns `true` when PCNTL extension available in PHP
     *
     * @return bool
     */
    public static function isAvailable(): bool
    {
        return extension_loaded('pcntl');
    }

    /**
     * Removes previous received signal state
     *
     * @param int $signo The signal number, see `pcntl_signal()`
     */
    public static function resetState(int $signo): void
    {
        self::$signalsState[$signo] = false;
    }

    /**
     * @param int $signo The signal number, see `pcntl_signal()`
     */
    private static function handleSignal(int $signo): void
    {
        self::$signalsState[$signo] = true;

        if (is_callable(self::$callbacks[$signo])) {
            call_user_func(self::$callbacks[$signo]);
        }
    }
}
