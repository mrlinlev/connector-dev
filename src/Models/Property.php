<?php

namespace Leveon\Connector\Models;

class Property extends AModel
{

    protected string $title;
    protected bool $options;
    protected bool $multiple;
    protected int $type;
    /**
     * @var PropertyTuning[]
     */
    protected array $scheme;

    /**
     * @var string[]
     */
    protected static array $valueableList = [
        'title',
        'options',
        'multiple',
        'type',
    ];

    /**
     * @var class-string[]
     */
    protected static array $lists = [
        'scheme' => PropertyTuning::class
    ];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getOptions(): bool
    {
        return $this->options;
    }

    public function setOptions(bool $options): static
    {
        $this->options = $options;
        return $this;
    }

    public function getMultiple(): bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): static
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return PropertyTuning[]
     */
    public function getScheme(): array
    {
        return $this->scheme;
    }

    /**
     * @param PropertyTuning[] $scheme
     * @return $this
     */
    public function setScheme(array $scheme): static
    {
        $this->scheme = $scheme;
        return $this;
    }

}