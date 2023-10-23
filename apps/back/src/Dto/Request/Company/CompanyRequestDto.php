<?php

declare(strict_types=1);

namespace App\Dto\Request\Company;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyRequestDto
{
    private UploadedFile|null $indentityFile = null;

    public function __construct(
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIndentityFile(): UploadedFile|null
    {
        return $this->indentityFile;
    }

    public function setIndentityFile(UploadedFile|null $indentityFile): void
    {
        $this->indentityFile = $indentityFile;
    }
}
