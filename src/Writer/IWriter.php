<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Writer;


interface IWriter
{
    public function write(string $string): void;

    public function error(string $string): void;
}
