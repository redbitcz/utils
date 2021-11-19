<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Lock;


class LockerFactory
{
    /** @var string */
    private $dir;

    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    public function create(string $name, int $blockMode = Locker::NON_BLOCKING): Locker
    {
        return new Locker($this->dir, $name, $blockMode);
    }
}
