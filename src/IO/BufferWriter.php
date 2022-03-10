<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

use RuntimeException;

class BufferWriter implements IOutStream
{
    /** @var resource */
    private $outputStream;

    /** @var resource */
    private $errorStream;

    /** @var FileWriter */
    private $writer;

    public function __construct()
    {
        $this->outputStream = self::createMemoryStream();
        $this->errorStream = self::createMemoryStream();

        $this->writer = new FileWriter($this->outputStream, $this->errorStream);
    }

    public function __destruct()
    {
        fclose($this->outputStream);
        fclose($this->errorStream);
    }

    public function write(string $string): void
    {
        $this->writer->write($string);
    }

    public function error(string $string): void
    {
        $this->writer->error($string);
    }

    public function getOutputStreamContent(): string
    {
        return self::getStreamContent($this->outputStream);
    }

    public function getErrorStreamContent(): string
    {
        return self::getStreamContent($this->errorStream);
    }

    /**
     * @return resource
     */
    private static function createMemoryStream()
    {
        $fileHandler = fopen('php://memory', 'wb+');

        if($fileHandler === false) {
            throw new RuntimeException("Unable to create Memory stream (php://memory)");
        }

        return $fileHandler;
    }

    /**
     * @param resource $fileHandler
     * @return string
     */
    private static function getStreamContent($fileHandler): string
    {
        return stream_get_contents($fileHandler, -1, 0);
    }
}
