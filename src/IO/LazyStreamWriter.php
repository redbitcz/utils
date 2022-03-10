<?php

declare(strict_types=1);

namespace Redbitcz\Utils\IO;

use LogicException;

/**
 * Same as `FileWriter`, but is opening file descriptors only on first write them
 */
class LazyStreamWriter implements IOutStream
{
    /** @var callable */
    private $outputStreamFactory;
    /** @var callable */
    private $errorStreamFactory;
    /** @var resource|null */
    private $outputStream;
    /** @var resource|null */
    private $errorStream;

    /**
     * @param callable $outputStreamFactory Callback which return writable stream handler resource
     * @param callable $errorStreamFactory Callback which return writable stream handler resource
     */
    public function __construct(callable $outputStreamFactory, callable $errorStreamFactory)
    {
        $this->outputStreamFactory = $outputStreamFactory;
        $this->errorStreamFactory = $errorStreamFactory;
    }

    public function write(string $string): void
    {
        if ($this->outputStream === null) {
            $this->outputStream = $this->createStream($this->outputStreamFactory);
        }

        fwrite($this->outputStream, $string);
    }

    public function error(string $string): void
    {
        if ($this->errorStream === null) {
            $this->errorStream = $this->createStream($this->errorStreamFactory);
        }

        fwrite($this->errorStream, $string);
    }

    private function createStream(callable $factory)
    {
        $stream = $factory();

        if (is_resource($stream) === false) {
            $type = is_object($stream) ? get_class($stream) : gettype($stream);
            throw new LogicException(
                "Stream factory callback must returns valid stream handler resource, {$type} returned"
            );
        }

        return $stream;
    }
}
