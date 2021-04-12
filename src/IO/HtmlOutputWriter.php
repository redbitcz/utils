<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

class HtmlOutputWriter implements IOutStream
{
    public function write(string $string): void
    {
        echo nl2br(htmlspecialchars($string));
    }

    public function error(string $string): void
    {
        echo nl2br(htmlspecialchars($string));
    }
}
