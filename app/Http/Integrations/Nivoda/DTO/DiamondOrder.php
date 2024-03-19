<?php

namespace App\Http\Integrations\Nivoda\DTO;

use App\Http\Integrations\Nivoda\Enums\DiamondOrderType;
use App\Http\Integrations\Nivoda\Enums\OrderDirection;
use Stringable;

class DiamondOrder implements Stringable
{
    public function __construct(
        public DiamondOrderType $type,
        public OrderDirection $direction = OrderDirection::ASCENDING,
    ) {
    }

    public function __toString(): string
    {
        return <<<GRAPHQL
        {
            type: {$this->type->value}
            direction: {$this->direction->value}
        }
        GRAPHQL;
    }
}
