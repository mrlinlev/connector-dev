<?php

namespace Leveon\Connector\Models;

class Offer extends AModelWithProperties
{
    protected ?string $title;
    protected ?string $article;
    protected ?int $type;
    protected ?int $brand;
    protected ?int $collection;
    protected ?int $accountingUnit;
    protected ?string $image;

    protected static array $valueableList = [
        'title',
        'article',
        'type',
        'brand',
        'collection',
        'accountingUnit',
        'image',
        'product',
    ];

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): Offer
    {
        $this->product = $product;
        return $this;
    }

    public function setTitle(?string $title): Offer
    {
        $this->title = $title;
        return $this;
    }

    public function setArticle(?string $article): Offer
    {
        $this->article = $article;
        return $this;
    }

    public function setType(?int $type): Offer
    {
        $this->type = $type;
        return $this;
    }

    public function setBrand(?int $brand): Offer
    {
        $this->brand = $brand;
        return $this;
    }

    public function setCollection(?int $collection): Offer
    {
        $this->collection = $collection;
        return $this;
    }

    public function setAccountingUnit(?int $accountingUnit): Offer
    {
        $this->accountingUnit = $accountingUnit;
        return $this;
    }

    public function setImage(?string $image): Offer
    {
        $this->image = $image;
        return $this;
    }
}