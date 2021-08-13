<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Lock;

use Redbitcz\Utils\Lock\Exception\DirectoryNotFoundException;
use Redbitcz\Utils\Lock\Exception\LockObtainException;

class Locker
{
    /**
     * `BLOCKING` mode - Blocking lock is waiting to release previous lock.
     * Be careful, it may cause to deadlock of PHP processes, because lock at filesystem is not subject of
     * `max_execution_time` limit!
     */
    public const BLOCKING = 0;

    /**
     * `NON_BLOCKING` mode - Non blocking lock is fail immediately with the Exception when lock obtaining is failed.
     */
    public const NON_BLOCKING = LOCK_NB;

    /** @var string */
    private $lockFile;

    /** @var resource|null */
    private $lockHandle;

    /** @var int */
    private $lockMode;

    /**
     * @param string $dir Path to lockfiles directory, must exists and must be writable.
     * @param string $name Lock name. Lockers with the same `$dir` and `$name` are interlocking.
     * @param int $blockMode `BLOCKING` is waiting to release previous lock, `NON_BLOCKING` doesn't wait, it fails immediately
     */
    public function __construct(string $dir, string $name, int $blockMode = self::NON_BLOCKING)
    {
        if (!is_dir($dir)) {
            throw new DirectoryNotFoundException("Directory '$dir' not found.");
        }

        $this->lockFile = $this->getLockfileName($dir, $name);
        $this->lockMode = LOCK_EX | $blockMode;
    }

    public function __destruct()
    {
        $this->unlock();
    }

    /**
     * Try to obtain lock, throw `LockObtainException` when lock obtaining failed or wait to release previous lock
     *
     * @throws LockObtainException
     * @see `\Redbitcz\Utils\Lock\Locker::__construct()` documentation of `$blockMode` argument
     */
    public function lock(): void
    {
        if ($this->lockHandle) {
            return;
        }

        $lockHandle = @fopen($this->lockFile, 'c+b'); // @ is escalated to exception
        if ($lockHandle === false) {
            $error = (error_get_last()['message'] ?? 'unknown error');
            throw new LockObtainException("Unable to create lockfile '{$this->lockFile}', $error");
        }

        if (@flock($lockHandle, $this->lockMode, $alreadyLocked) === false) { // @ is escalated to exception
            throw new LockObtainException("Unable to obtain exclusive lock on lockfile '{$this->lockFile}'");
        }

        // Prevent leak already locked advisory lock
        if (($this->lockMode & self::BLOCKING) === 0 && $alreadyLocked === 1) {
            @flock($lockHandle, LOCK_UN);
            throw new LockObtainException(
                "Unable to obtain exclusive lock on lockfile '{$this->lockFile}', already locked"
            );
        }

        $this->lockHandle = $lockHandle;
    }

    /**
     * Unlock current lock and try to remove lockfile
     */
    public function unlock(): void
    {
        if ($this->lockHandle === null) {
            return;
        }

        flock($this->lockHandle, LOCK_UN);
        fclose($this->lockHandle);
        $this->lockHandle = null;

        @unlink($this->lockFile); // @ file may not exists yet (purge tempdir, etc.)
    }

    private function getLockfileName(string $tempDir, string $namespace): string
    {
        return $tempDir . '/rbt_' . urlencode($namespace) . '.lock';
    }
}
