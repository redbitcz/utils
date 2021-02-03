<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

class OutputWriter implements IOutStream
{
    public function write(string $string): void
    {
        echo $string;
    }

    public function error(string $string): void
    {
        echo $string;
    }
}
