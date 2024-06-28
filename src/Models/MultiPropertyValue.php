<?php

namespace Leveon\Connector\Models;

class MultiPropertyValue extends APropertyValue
{

    protected array $value;

    protected static array $valueableList = ['property'];
    protected static array $lists = [
        'value' => AModel::class
    ];


    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(array $value): static
    {
        $this->value = $value;
        return $this;
    }
}