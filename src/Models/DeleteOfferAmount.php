<?php

namespace Leveon\Connector\Models;

class DeleteOfferAmount extends AModel
{

    protected ?string $offer;
    protected ?int $store;

    public static array $compressable = [
        'offer',
        'store',
    ];

    public static $final = null;

    protected static array $valueableList = [
        'offer',
        'store',
    ];

    public function toJSON($rules = []): object
    {
        $result = [];
        if (in_array('offer', $rules) && $this->offer !== null) $result['offer'] = $this->offer;
        if (in_array('store', $rules) && $this->store !== null) $result['store'] = $this->store;
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

    public function getStore(): int
    {
        return $this->store;
    }

    public function setStore($store): static
    {
        $this->store = $store;
        return $this;
    }
}