<?php

namespace Leveon\Connector\Models;

class Collection extends AModelWithProperties
{

    protected string $title = '';
    protected ?string $image = null;

    /**
     * @var string[]
     */
    protected static array $valueableList = ['title', 'image'];

    public function getTitle(): string
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
}