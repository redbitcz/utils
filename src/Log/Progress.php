<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Log;

use Psr\Log\LoggerInterface;

class Progress
{
    /** @var LoggerInterface */
    private $logger;
    /** @var int */
    private $totalSteps;

    /** @var float|string */
    private $startTime;

    /** @var float|string */
    private $lastTime;

    /** @var int */
    private $step;

    public function __construct(LoggerInterface $logger, int $totalSteps)
    {
        $this->logger = $logger;
        $this->totalSteps = $totalSteps;
        $this->startTime = $this->lastTime = microtime(true);
        $this->step = 0;
    }

    public function step(?string $text = null): void
    {
        $this->step++;
        $this->logger->debug(
            sprintf(
                '%s step %' . strlen((string)$this->totalSteps) . 'd/%d: %s',
                $this->getTimeAsString(),
                $this->step,
                $this->totalSteps,
                $text ?? '-'
            )
        );
    }

    private function getTimeAsString(): string
    {
        $now = microtime(true);
        $fromStart = $now - $this->startTime;
        $fromLast = $now - $this->lastTime;
        $this->lastTime = $now;
        return sprintf(
            '[%6.3fs/%6.3fs]',
            round($fromLast, 3),
            round($fromStart, 3)
        );
    }
}
