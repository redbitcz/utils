<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Redbitcz\Utils\IO\IOutStream;

class SectionLogger extends Logger
{
    /** @var string */
    private $section;

    /** @var string */
    private $separator;

    /**
     * @param string[] $errorLevels
     */
    public function __construct(IOutStream $writer, string $section, string $separator = '/', array $errorLevels = [])
    {
        parent::__construct($writer, $errorLevels);
        $this->section = $section;
        $this->separator = $separator;
    }

    public function log($level, $message, array $context = []): void
    {
        $interpolatedMessage = sprintf(
            "[%s] %s: {%s} %s\n",
            date('Y-m-d H:i:s'),
            strtoupper((string)$level),
            $this->section,
            $this->interpolate($message, $context)
        );

        if (isset($this->errorLevels[$level])) {
            $this->writer->error($interpolatedMessage);
        } else {
            $this->writer->write($interpolatedMessage);
        }
    }

    /**
     * @todo PHP 7.4 return type -> self
     */
    public function withStandardErrorLevels(): Logger
    {
        return new self($this->writer, $this->section, $this->separator, self::STANDARD_ERROR_LEVELS);
    }

    public function section(string $section, string $separator = null): SectionLoggerInterface
    {
        $separator = $separator ?? $this->separator;
        $section = $this->section . $separator . $section;
        return parent::section($section, $separator);
    }
}
