<?php

namespace Leveon\Connector\Models;

abstract class APrice extends AModel
{

    protected ?float $value;
    protected ?int $currency;
    protected ?int $priceType;

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue($value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getCurrency(): ?int
    {
        return $this->currency;
    }

    public function setCurrency($currency): static
    {
        $this->currency = $currency;
        return $this;
    }

    public function getPriceType(): ?int
    {
        return $this->priceType;
    }

    public function setPriceType($priceType): static
    {
        $this->priceType = $priceType;
        return $this;
    }
}