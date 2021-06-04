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

    /** @var int */
    private $strlenTotalSteps;

    public function __construct(LoggerInterface $logger, int $totalSteps)
    {
        $this->logger = $logger;
        $this->totalSteps = $totalSteps;
        $this->startTime = $this->lastTime = microtime(true);
        $this->step = 0;
        $this->strlenTotalSteps = strlen((string)$totalSteps);
    }

    public function step(?string $text = null, ?string $prefix = null): void
    {
        $this->step++;
        $this->logger->debug(
            sprintf(
                '%s%s %' . $this->strlenTotalSteps . 'd/%d: %s',
                $prefix ?? '',
                $this->getTimeAsString(),
                $this->step,
                $this->totalSteps,
                $text ?? '-'
            )
        );
    }

    public function stepInStep(?string $text = null, ?string $prefix = null, int $deep = 1): void
    {
        $this->logger->debug(
            sprintf(
                '%s%s%s%s ╰→ %s',
                $prefix ?? '',
                $this->getTimeAsString(),
                str_repeat(' ', ($this->strlenTotalSteps * 2)),
                str_repeat(' ', $deep * 3),
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
