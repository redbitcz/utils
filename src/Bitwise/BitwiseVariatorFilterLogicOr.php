<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Bitwise;

class BitwiseVariatorFilterLogicOr extends BitwiseVariatorFilter
{
    /** @var BitwiseVariatorFilter */
    private $previous;

    public function __construct(BitwiseVariator $variator, BitwiseVariatorFilter $previous)
    {
        parent::__construct($variator);
        $this->previous = $previous;
    }

    public function getFilter(): int
    {
        return parent::getFilter() | $this->previous->getFilter();
    }

    /**
     * @return int[]
     */
    public function getValues(): array
    {
        $variator = new BitwiseVariator($this->getFilter() & $this->variator->getBits());

        $meVariations = $variator->variate(parent::getFilter(), parent::getValues());
        $prevVariations = $variator->variate($this->previous->getFilter(), $this->previous->getValues());
        return array_unique(array_merge($meVariations, $prevVariations));
    }
}
