<?php

namespace Leveon\Connector\Models;

class Offer extends AModelWithProperties
{
    protected ?string $title = '';
    protected ?string $article = '';
    protected ?int $type = null;
    protected ?int $product = null;
    protected ?int $brand = null;
    protected ?int $collection = null;
    protected ?int $accountingUnit = null;
    protected ?string $image = null;

    /**
     * @var string[]
     */
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

    public function getProduct(): ?int
    {
        return $this->product;
    }
    public function setProduct(?int $product): Offer
    {
        $this->product = $product;
        return $this;
    }

    public function setTitle(string $title): Offer
    {
        $this->title = $title;
        return $this;
    }

    public function setArticle(?string $article): Offer
    {
        $this->article = $article;
        return $this;
    }

    public function setType(int $type): Offer
    {
        $this->type = $type;
        return $this;
    }

    public function setBrand(int $brand): Offer
    {
        $this->brand = $brand;
        return $this;
    }

    public function setCollection(?int $collection): Offer
    {
        $this->collection = $collection;
        return $this;
    }

    public function setAccountingUnit(int $accountingUnit): Offer
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