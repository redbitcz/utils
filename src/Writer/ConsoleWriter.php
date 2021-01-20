<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Writer;


class ConsoleWriter implements IWriter
{
    public function write(string $string): void
    {
        fwrite(STDOUT, $string);
    }

    public function error(string $string): void
    {
        fwrite(STDERR, $string);
    }

}
