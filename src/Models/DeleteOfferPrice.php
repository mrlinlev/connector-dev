<?php

namespace Leveon\Connector\Models;

class DeleteOfferPrice extends ADeletePrice
{

    protected string $offer;

    public static array $compressable = [
        'offer',
        'priceType',
    ];

    public static ?string $final = null;

    protected static array $valueableList = [
        'product',
        'store',
    ];


    public function getOffer(): string
    {
        return $this->offer;
    }

    public function setOffer($offer): static
    {
        $this->offer = $offer;
        return $this;
    }
}