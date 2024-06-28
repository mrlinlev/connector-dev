<?php

namespace Leveon\Connector\Models;

class OfferAmount extends AModel
{

    protected ?string $offer;
    protected ?int $store;
    protected string | float | null $amount;

    public static array $compressable = [
        'offer',
        'store',
    ];

    public static ?string $final = 'amount';

    protected static array $valueableList = [
        'amount',
        'offer',
        'store',
    ];

    public function toJSON($rules = []): object
    {
        $result = [];
        if (in_array('offer', $rules) && $this->offer !== null) $result['offer'] = $this->offer;
        if (in_array('store', $rules) && $this->store !== null) $result['store'] = $this->store;
        if (in_array('amount', $rules) && $this->amount !== null) $result['amount'] = $this->amount;
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

    public function getAmount(): float|string
    {
        return $this->amount;
    }

    public function setAmount($amount): static
    {
        $this->amount = $amount;
        return $this;
    }
}