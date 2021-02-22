<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

class ConsoleWriter extends FileWriter
{
    public function __construct()
    {
        parent::__construct(STDOUT, STDERR);
    }
}
