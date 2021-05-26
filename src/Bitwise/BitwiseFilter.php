<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Bitwise;

abstract class BitwiseFilter
{
    /** @var int */
    private $filter = 0;

    /** @var int */
    private $values = 0;

    /**
     * @param int $bite
     * @return static
     */
    public function must(int $bite): self
    {
        $this->filter |= $bite;
        $this->values |= $bite;
        return $this;
    }

    /**
     * @param int $bite
     * @return static
     */
    public function mustNot(int $bite): self
    {
        $this->filter |= $bite;
        $this->values &= ~$bite;
        return $this;
    }

    public function getFilter(): int
    {
        return $this->filter;
    }

    /**
     * @return int[]
     */
    public function getValues(): array
    {
        return [$this->values];
    }
}
