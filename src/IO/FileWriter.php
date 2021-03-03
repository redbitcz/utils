<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

class FileWriter implements IOutStream
{
    /** @var resource */
    private $outputStream;
    /** @var resource */
    private $errorStream;

    /**
     * @param resource $outputStream
     * @param resource $errorStream
     */
    public function __construct($outputStream, $errorStream)
    {
        $this->outputStream = $outputStream;
        $this->errorStream = $errorStream;
    }

    public function write(string $string): void
    {
        fwrite($this->outputStream, $string);
    }

    public function error(string $string): void
    {
        fwrite($this->errorStream, $string);
    }
}
