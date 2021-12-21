<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
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
        $text = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            strtoupper((string)$level),
            $this->interpolate($message, $context)
        );
        if (in_array($level, [LogLevel::ERROR, LogLevel::CRITICAL, LogLevel::ALERT, LogLevel::EMERGENCY], true)) {
            $this->writer->error($text);
        } else {
            $this->writer->write($text);
        }
    }

    public function section(string $section, string $separator = '/'): SectionLoggerInterface
    {
        return new SectionLogger($this->writer, $section, $separator);
    }
}
