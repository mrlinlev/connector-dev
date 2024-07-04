<?php

namespace Leveon\Connector\Models;

class Product extends AModel
{
    protected ?string $title = '';
    protected ?string $image = null;
    protected ?int $brand = null;
    protected ?int $collection = null;
    protected ?int $type = null;
    protected ?int $accountingUnit = null;

    /**
     * @var string[]
     */
    protected static array $valueableList = [
        'title',
        'image',
        'brand',
        'collection',
        'type',
        'accountingUnit'
    ];

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getBrand(): ?int
    {
        return $this->brand;
    }

    public function setBrand(int $brand): static
    {
        $this->brand = $brand;
        return $this;
    }

    public function getCollection(): ?int
    {
        return $this->collection;
    }

    public function setCollection(?int $collection): static
    {
        $this->collection = $collection;
        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getAccountingUnit(): ?int
    {
        return $this->accountingUnit;
    }

    public function setAccountingUnit(int$accountingUnit): static
    {
        $this->accountingUnit = $accountingUnit;
        return $this;
    }
}