<?php

namespace App\Services\Saloon;

use Illuminate\Contracts\Support\Arrayable;
use Stringable;

class ArgumentSet implements Stringable
{
    public array $arguments = [];

    public function __construct(array $arguments = [], protected string $glue = ', ')
    {
        foreach ($arguments as $name => $value) {
            if ($value instanceof Argument) {
                $this->add($value);
            } else {
                $this->add($name, $value);
            }
        }
    }

    public function __toString(): string
    {
        $validArgs = array_filter($this->arguments, static fn ($argument) => (string) $argument !== '');

        if (empty($validArgs)) {
            return '';
        }

        return "(" . implode($this->glue, $validArgs) . ")";
    }

    public function add(Argument|string $name, mixed $value = null): static
    {
        $this->arguments[] = $name instanceof Argument ? $name : new Argument($name, $value);

        return $this;
    }
}
