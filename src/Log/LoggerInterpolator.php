<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

trait LoggerInterpolator
{
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
