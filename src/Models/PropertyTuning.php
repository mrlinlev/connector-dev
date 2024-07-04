<?php

namespace Leveon\Connector\Models;

class PropertyTuning extends AModel
{

    protected mixed $defaultValue = null;
    protected ?int $defaultUnit = null;
    protected ?int $unitsGroup = null;
    protected ?int $storageUnit = null;
    protected ?int $displayUnit = null;

    /**
     * @var array|string[]
     */
    protected static array $valueableList = [
        'defaultValue',
        'defaultUnit',
        'unitsGroup',
        'storageUnit',
        'displayUnit',
    ];

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function setDefaultValue($defaultValue): static
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function getDefaultUnit(): ?int
    {
        return $this->defaultUnit;
    }

    public function setDefaultUnit($defaultUnit): static
    {
        $this->defaultUnit = $defaultUnit;
        return $this;
    }

    public function getUnitsGroup(): ?int
    {
        return $this->unitsGroup;
    }

    public function setUnitsGroup($unitsGroup): static
    {
        $this->unitsGroup = $unitsGroup;
        return $this;
    }

    public function getStorageUnit(): ?int
    {
        return $this->storageUnit;
    }

    public function setStorageUnit($storageUnit): static
    {
        $this->storageUnit = $storageUnit;
        return $this;
    }

    public function getDisplayUnit(): ?int
    {
        return $this->displayUnit;
    }

    public function setDisplayUnit($displayUnit): static
    {
        $this->displayUnit = $displayUnit;
        return $this;
    }
}