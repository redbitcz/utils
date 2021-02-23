<?php

declare(strict_types=1);

namespace Tests;

use RuntimeException;

class FileUtils
{
    /**
     * @return resource
     */
    public static function createMemoryStream()
    {
        $fileHandler = fopen('php://memory', 'wb+');

        self::validateResource($fileHandler);

        return $fileHandler;
    }

    /**
     * @param resource $fileHandler
     * @return string
     */
    public static function getStreamContent($fileHandler): string
    {
        self::validateResource($fileHandler);

        return stream_get_contents($fileHandler, -1, 0);
    }

    /**
     * @param resource|mixed $fileHandler
     */
    public static function validateResource($fileHandler): void
    {
        if (is_resource($fileHandler) === false) {
            $type = gettype($fileHandler);
            throw new RuntimeException("File handler in test is invalid, resource type required, {$type} instead");
        }
    }
}
