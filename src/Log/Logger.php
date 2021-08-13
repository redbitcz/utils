<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Redbitcz\Utils\IO\IOutStream;

class Logger implements LoggerInterface, SectionLoggerInterface
{
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

    /**
     * @param mixed $message
     * @param mixed[] $context
     * @return string
     */
    protected function interpolate($message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $value) {
            if (is_array($value) === false && (is_object($value) === false || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = (string)$value;
            }
        }

        return strtr((string)$message, $replace);
    }
}
