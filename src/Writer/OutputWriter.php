<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Writer;


class OutputWriter implements IWriter
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
