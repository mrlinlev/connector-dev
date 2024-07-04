<?php

namespace Leveon\Connector\Models;

class ProductType extends AModelWithProperties
{

    protected string $title = '';
    protected int $parent = 0;

    /**
     * @var string[]
     */
    protected static array $valueableList = ['title', 'parent'];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getParent(): int
    {
        return $this->parent;
    }

    public function setParent(int $parent): static
    {
        $this->parent = $parent;
        return $this;
    }
}