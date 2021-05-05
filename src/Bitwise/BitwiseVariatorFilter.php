<?php

declare(strict_types=1);

namespace Redbitcz\Utils\Bitwise;

class BitwiseVariatorFilter extends BitwiseFilter
{
    /** @var BitwiseVariator */
    protected $variator;

    public function __construct(BitwiseVariator $variator)
    {
        $this->variator = $variator;
    }

    public function or(): BitwiseVariatorFilterLogicOr
    {
        return new BitwiseVariatorFilterLogicOr($this->variator, $this);
    }

    /**
     * @return int[]
     */
    public function variate(): array
    {
        return $this->variator->variate($this->getFilter(), $this->getValues());
    }
}
