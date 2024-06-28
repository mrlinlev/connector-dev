<?php

namespace Leveon\Connector\Models;

class Collection extends AModelWithProperties
{

    protected string $title;
    protected ?string $image;

    protected static array $valueableList = ['title', 'image'];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage($image): static
    {
        $this->image = $image;
        return $this;
    }
}