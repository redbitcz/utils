<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

use Closure;
use RuntimeException;

class MemoryWriter implements IBufferWriter
{
    /** @var resource|null */
    private $outputStream;

    /** @var resource|null */
    private $errorStream;

    /** @var FileWriter */
    private $writer;

    public function __construct()
    {
        $this->writer = new LazyStreamWriter(
            Closure::fromCallable([$this, 'createOutputStream']),
            Closure::fromCallable([$this, 'createErrorStream']),
        );
    }

    public function __destruct()
    {
        if ($this->hasOutputContent()) {
            fclose($this->outputStream);
        }

        if ($this->hasErrorContent()) {
            fclose($this->errorStream);
        }
    }

    public function write(string $string): void
    {
        $this->writer->write($string);
    }

    public function error(string $string): void
    {
        $this->writer->error($string);
    }

    private function createOutputStream()
    {
        return $this->outputStream = $this->createMemoryStream();
    }

    private function createErrorStream()
    {
        return $this->errorStream = $this->createMemoryStream();
    }

    public function hasOutputContent(): bool
    {
        return isset($this->outputStream);
    }

    public function getOutputContent(): string
    {
        return $this->hasOutputContent() ? $this->getStreamContent($this->outputStream) : '';
    }

    public function hasErrorContent(): bool
    {
        return isset($this->errorStream);
    }

    public function getErrorContent(): string
    {
        return $this->hasErrorContent() ? $this->getStreamContent($this->errorStream) : '';
    }

    /**
     * @return resource
     */
    private function createMemoryStream()
    {
        $fileHandler = fopen('php://memory', 'wb+');

        if ($fileHandler === false) {
            throw new RuntimeException("Unable to create Memory stream (php://memory)");
        }

        return $fileHandler;
    }

    /**
     * @param resource $fileHandler
     * @return string
     */
    private function getStreamContent($fileHandler): string
    {
        return stream_get_contents($fileHandler, -1, 0);
    }
}
