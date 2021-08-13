<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Psr\Log\LoggerInterface;

interface SectionLoggerInterface extends LoggerInterface
{
    public function section(string $section, string $separator = '/'): SectionLoggerInterface;
}
