<?php

namespace Leveon\Connector\Models;

class FileValue extends AValue
{

    protected string $file;
    protected ?string $title;

    protected static array $valueableList = ['file', 'title'];

    public static function Url(string $file, ?string $ext = null, ?string $title = null): FileValue
    {
        $ext = $ext === null ? '' : $ext;
        return (new static())->setFile("url:$ext:$file")->setTitle($title);
    }

    public static function B64(string $file, string $ext, ?string $title = null): FileValue
    {
        $file = base64_encode($file);
        return (new static())->setFile("url:$ext:$file")->setTitle($title);
    }

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

}