<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

interface IBufferWriter extends IOutStream
{
    public function getOutputContent(): string;

    public function getErrorContent(): string;
}
