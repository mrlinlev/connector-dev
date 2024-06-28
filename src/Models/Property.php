<?php

namespace Leveon\Connector\Models;

class Property extends AModel
{

    protected string $title;
    protected bool $options;
    protected bool $multiple;
    protected int $type;
    protected array $scheme;

    protected static array $valueableList = [
        'title',
        'options',
        'multiple',
        'type',
    ];

    protected static array $lists = [
        'scheme' => PropertyTuning::class
    ];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle($title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getOptions(): bool
    {
        return $this->options;
    }

    public function setOptions($options): static
    {
        $this->options = $options;
        return $this;
    }

    public function getMultiple(): bool
    {
        return $this->multiple;
    }

    public function setMultiple($multiple): static
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType($type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getScheme(): array
    {
        return $this->scheme;
    }

    public function setScheme($scheme): static
    {
        $this->scheme = $scheme;
        return $this;
    }

}