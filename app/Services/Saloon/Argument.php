<?php

namespace App\Services\Saloon;

use Illuminate\Contracts\Support\Arrayable;
use Stringable;

/**
 * @template TValue
 */
class Argument implements Stringable
{
    /**
     * @param  TValue  $value
     */
    public function __construct(
        public string $name,
        /**
         * @var TValue
         */
        public mixed $value,
    ) {
    }

    public function __toString(): string
    {
        if (is_null($this->value)) {
            return '';
        }

        return "{$this->name}: {$this->value}";
    }
}
