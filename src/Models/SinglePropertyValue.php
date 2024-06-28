<?php

namespace Leveon\Connector\Models;

class SinglePropertyValue extends APropertyValue
{

    protected AValue $value;

    protected static array $valueableList = ['property'];
    protected static array $entitiesList = [
        'value' => AModel::class
    ];

    public function getValue(): AValue
    {
        return $this->value;
    }

    public function setValue(AValue $value): static
    {
        $this->value = $value;
        return $this;
    }

}