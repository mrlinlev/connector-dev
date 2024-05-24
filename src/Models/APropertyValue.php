<?php


namespace Leveon\Connector\Models;


class APropertyValue extends AModel{

    protected $property;

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $property
     * @return APropertyValue
     */
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

}