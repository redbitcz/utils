<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Redbitcz\Utils\IO\IOutStream;

class Logger implements LoggerInterface, SectionLoggerInterface
{
    use LoggerInterpolator;
    use LoggerTrait;

    /** @var IOutStream */
    protected $writer;

    public function __construct(IOutStream $writer)
    {
        $this->writer = $writer;
    }

    public function log($level, $message, array $context = []): void
    {
        $this->writer->write(
            sprintf(
                "[%s] %s: %s\n",
                date('Y-m-d H:i:s'),
                strtoupper((string)$level),
                $this->interpolate($message, $context)
            )
        );
    }

    public function section(string $section, string $separator = '/'): SectionLoggerInterface
    {
        return new SectionLogger($this->writer, $section, $separator);
    }
}
