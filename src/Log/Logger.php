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
    /** @var array<string, bool> */
    protected $errorLevels = [];

    public function __construct(IOutStream $writer)
    {
        $this->writer = $writer;
    }

    public function log($level, $message, array $context = []): void
    {
        $interpolatedMessage = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            strtoupper((string)$level),
            $this->interpolate($message, $context)
        );
        if (isset($this->errorLevels[$level])) {
            $this->writer->error($interpolatedMessage);
        } else {
            $this->writer->write($interpolatedMessage);
        }
    }

    /**
     * @param string[] $levels
     */
    public function setErrorLevels(array $levels): void
    {
        $this->errorLevels = [];
        foreach ($levels as $level) {
            $this->addErrorLevel($level);
        }
    }

    public function addErrorLevel(string $level): void
    {
        $this->errorLevels[$level] = true;
    }

    public function section(string $section, string $separator = '/'): SectionLoggerInterface
    {
        return new SectionLogger($this->writer, $section, $separator);
    }
}
