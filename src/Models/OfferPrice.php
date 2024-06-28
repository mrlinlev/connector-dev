<?php

namespace Leveon\Connector\Models;

class OfferPrice extends APrice
{

    protected ?string $offer;

    public static array $compressable = [
        'currency',
        'offer',
        'priceType',
    ];

    public static ?string $final = 'value';

    protected static array $valueableList = [
        'value',
        'currency',
        'offer',
        'priceType',
    ];

    public function toJSON($rules = []): object
    {
        $result = [];
        if (in_array('currency', $rules) && $this->currency !== null) $result['currency'] = $this->currency;
        if (in_array('offer', $rules) && $this->offer !== null) $result['offer'] = $this->offer;
        if (in_array('priceType', $rules) && $this->priceType !== null) $result['priceType'] = $this->priceType;
        if (in_array('value', $rules) && $this->value !== null) $result['value'] = $this->value;
        return (object)$result;
    }


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