<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use Redbitcz\Utils\IO\IOutStream;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    /** @var IOutStream */
    private $writer;

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

    private function interpolate($message, array $context = []): string
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
