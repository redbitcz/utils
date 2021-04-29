<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Pcntl;

/**
 * Few most used Unix-like process signals definition to simplest use PCNTL based tools without PHP pcntl extension
 *
 * @link https://php.net/manual/en/pcntl.constants.php
 */
class Signals
{
    public const SIGHUP = 1;
    public const SIGINT = 2;
    public const SIGQUIT = 3;
    public const SIGILL = 4;
    public const SIGTRAP = 5;
    public const SIGABRT = 6;
    public const SIGIOT = 6;
    public const SIGFPE = 8;
    public const SIGKILL = 9;
    public const SIGSEGV = 11;
    public const SIGPIPE = 13;
    public const SIGALRM = 14;
    public const SIGTERM = 15;
}
