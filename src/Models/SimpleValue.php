<?php

namespace Leveon\Connector\Models;

class SimpleValue extends AValue
{

    protected mixed $value;

    protected static array $valueableList = ['value'];

    public static function V(mixed $value): SimpleValue
    {
        return (new static())->setValue($value);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue($value): static
    {
        $this->value = $value;
        return $this;
    }
}