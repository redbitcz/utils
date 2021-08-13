<?php

declare(strict_types=1);

namespace Tests\Lock;

use Redbitcz\Utils\Lock\Exception\LockObtainException;
use Redbitcz\Utils\Lock\Locker;
use Tester\Assert;
use Tester\Environment;
use Tester\Helpers;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';

Environment::setup();

/** @testCase */
class LockerTest extends TestCase
{
    private const TEMP_DIR = __DIR__ . '/temp/lock';
    private const LOCK_DIR = __DIR__ . '/lock';

    protected function setUp(): void
    {
        @mkdir(self::LOCK_DIR, 0777, true);
        @mkdir(self::TEMP_DIR, 0777, true);
        Environment::lock('lock', self::LOCK_DIR);
        Helpers::purge(self::TEMP_DIR);
    }

    public function testLock(): void
    {
        $locker = new Locker(self::TEMP_DIR, __METHOD__, Locker::NON_BLOCKING);

        $locker->lock();
        $locker->unlock();

        // And again
        $locker = new Locker(self::TEMP_DIR, __METHOD__, Locker::NON_BLOCKING);

        $locker->lock();
        $locker->unlock();

        Assert::true(true);
    }

    public function testBlockingLock(): void
    {
        $locker = new Locker(self::TEMP_DIR, __METHOD__, Locker::BLOCKING);

        $locker->lock();
        $locker->unlock();

        // And again
        $locker = new Locker(self::TEMP_DIR, __METHOD__, Locker::BLOCKING);

        $locker->lock();
        $locker->unlock();

        Assert::true(true);
    }

    public function testUnacquirableLock(): void
    {
        Assert::exception(
            function () {
                $locker1 = new Locker(self::TEMP_DIR, __METHOD__, Locker::NON_BLOCKING);
                $locker2 = new Locker(self::TEMP_DIR, __METHOD__, Locker::NON_BLOCKING);

                $locker1->lock();
                $locker2->lock();

                $locker1->unlock();
                $locker2->unlock();
            },
            LockObtainException::class
        );
    }
}

(new LockerTest())->run();
