<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Bitwise;

class BitwiseVariator
{
    /** @var int */
    private $bits;

    public static function create(int $bits): BitwiseVariatorFilter
    {
        return (new self($bits))->filter();
    }

    public function __construct(int $bits)
    {
        $this->bits = $bits;
    }

    public function filter(): BitwiseVariatorFilter
    {
        return new BitwiseVariatorFilter($this);
    }

    /**
     * @param int $filter
     * @param int[] $values
     * @return int[]
     */
    public function variate(int $filter, array $values): array
    {
        $filter &= $this->bits;
        $cleaner = static function ($value) use ($filter) {
            return $value & $filter;
        };
        $values = array_map($cleaner, $values);
        $qbits = $this->bits & ~$filter;

        $variations = $values;
        for ($weight = 1; $weight <= $qbits; $weight <<= 1) {
            $bit = $qbits & $weight;
            if ($bit) {
                foreach ($variations as $variation) {
                    $variations[] = $variation | $bit;
                }
            }
        }
        return $variations;
    }

    /**
     * @return int
     * @internal
     */
    public function getBits(): int
    {
        return $this->bits;
    }
}
