<?php

namespace Leveon\Connector\Models;

class Relation extends AModel
{

    protected ?int $relation;
    protected ?string $source;
    protected ?string $target;

    public static array $compressable = [
        'relation',
        'source',
        'target',
    ];

    public static ?string $final = null;

    protected static array $valueableList = [
        'relation',
        'source',
        'target',
    ];

    public function toJSON($rules = []): object
    {
        $result = [];
        if (in_array('relation', $rules) && $this->relation !== null) $result['relation'] = $this->relation;
        if (in_array('source', $rules) && $this->source !== null) $result['source'] = $this->source;
        if (in_array('target', $rules) && $this->target !== null) $result['target'] = $this->target;
        return (object)$result;
    }

    public function getRelation(): ?int
    {
        return $this->relation;
    }

    public function setRelation($relation): static
    {
        $this->relation = $relation;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource($source): static
    {
        $this->source = $source;
        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget($target): static
    {
        $this->target = $target;
        return $this;
    }
}