<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Str;

class BuildFileUpload extends Field
{
    protected string $view = 'filament.forms.components.build-file-upload';

    protected function setUp(): void
    {
        parent::setUp();
        $this->required();
    }

    protected string $filenameField = 'filename';

    protected string $sizeField = 'size_bytes';

    protected string $sha256Field = 'sha256';

    public function filenameField(string $name): static
    {
        $this->filenameField = $name;

        return $this;
    }

    public function sizeField(string $name): static
    {
        $this->sizeField = $name;

        return $this;
    }

    public function sha256Field(string $name): static
    {
        $this->sha256Field = $name;

        return $this;
    }

    public function getFilenameStatePath(): string
    {
        return Str::beforeLast($this->getStatePath(), '.').'.'.$this->filenameField;
    }

    public function getSizeStatePath(): string
    {
        return Str::beforeLast($this->getStatePath(), '.').'.'.$this->sizeField;
    }

    public function getSha256StatePath(): string
    {
        return Str::beforeLast($this->getStatePath(), '.').'.'.$this->sha256Field;
    }
}
