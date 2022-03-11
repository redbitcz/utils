<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

interface IBufferWriter extends IOutStream
{
    public function hasOutputContent(): bool;

    public function getOutputContent(): string;

    public function hasErrorContent(): bool;

    public function getErrorContent(): string;
}
