<?php

namespace Leveon\Connector\Models;

abstract class ADeletePrice extends AModel
{

    protected ?int $priceType;

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