<?php


namespace Leveon\Connector\Models;


class APropertyValue extends AModel{

    protected int $property;

    /**
     * @return int
     */
    public function getProperty(): int
    {
        return $this->property;
    }

    /**
     * @param int $property
     * @return APropertyValue
     */
    public function setProperty(int $property): static
    {
        $this->property = $property;
        return $this;
    }

}