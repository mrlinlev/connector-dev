<?php

namespace Leveon\Connector\Models;

class ProductType extends AModelWithProperties
{

    protected string $title;
    protected int $parent = 0;

    protected static array $valueableList = ['title', 'parent'];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getParent(): int
    {
        return $this->parent;
    }

    public function setParent($parent): static
    {
        $this->parent = $parent;
        return $this;
    }
}