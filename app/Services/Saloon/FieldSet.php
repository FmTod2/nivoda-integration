<?php

namespace App\Services\Saloon;

use Illuminate\Contracts\Support\Arrayable;
use Stringable;

class FieldSet implements Stringable
{
    public function __construct(
        public array $fields = [],
    ) {
    }

    protected function format(array $fields): string
    {
        $body = '';

        foreach ($fields as $key => $field) {
            if (is_string($field)) {
                $body .= "{$field},\n";
            }

            if ($field instanceof Stringable) {
                $body .= "{$key} {\n";
                $body .= $field;
                $body .= "},\n";
            }

            if (is_array($field) || $field instanceof Arrayable) {
                $body .= "{$key} {\n";
                $body .= $this->format($field);
                $body .= "},\n";
            }
        }

        return $body;
    }

    public function __toString(): string
    {
        return $this->format($this->fields);
    }
}
