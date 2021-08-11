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

    public function __construct(IOutStream $writer, string $section, string $separator = '/')
    {
        parent::__construct($writer);
        $this->section = $section;
        $this->separator = $separator;
    }

    public function log($level, $message, array $context = []): void
    {
        $this->writer->write(
            sprintf(
                "[%s] %s: {%s} %s\n",
                date('Y-m-d H:i:s'),
                strtoupper((string)$level),
                $this->section,
                $this->interpolate($message, $context)
            )
        );
    }

    public function section(string $section, string $separator = null): SectionLoggerInterface
    {
        $separator = $separator ?? $this->separator;
        $section = $this->section . $separator . $section;
        return parent::section($section, $separator);
    }
}
