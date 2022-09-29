<?php

declare(strict_types=1);

namespace App\Domain\Model\Storable;

use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use SplFileInfo;

use function Safe\fopen;

abstract class Storable
{
    protected SplFileInfo $fileInfo;
    private string $filename;

    /**
     * @param resource $resource
     */
    final public function __construct(string $filename, private $resource, bool $overrideFilename = true)
    {
        $this->fileInfo = new SplFileInfo($filename);
        $this->filename = $overrideFilename === true ?
            Uuid::uuid4()->toString() :
            $filename;
    }

    public function getFilename(): string
    {
        return $this->filename . '.' . $this->getExtension();
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getExtension(): string
    {
        return $this->fileInfo->getExtension();
    }

    /**
     * @param UploadedFileInterface[] $uploadedFiles
     *
     * @return static[]
     */
    public static function createAllFromUploadedFiles(array $uploadedFiles): array
    {
        $storables = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $storables[] = self::createFromUploadedFile($uploadedFile);
        }

        return $storables;
    }

    public static function createFromUploadedFile(UploadedFileInterface $uploadedFile): static
    {
        $fileName = $uploadedFile->getClientFilename();
        $resource = $uploadedFile->getStream()->detach();

        if ($fileName === null) {
            throw new RuntimeException(
                'Filename from uploaded file is null'
            );
        }

        if ($resource === null) {
            throw new RuntimeException(
                'Resource from uploaded file is null'
            );
        }

        return new static(filename: $fileName, resource: $resource);
    }

    /**
     * @param string[] $paths
     *
     * @return static[]
     */
    public static function createAllFromPaths(array $paths): array
    {
        $storables = [];

        foreach ($paths as $path) {
            $storables[] = self::createFromPath($path);
        }

        return $storables;
    }

    public static function createFromPath(string $path): static
    {
        $file     = new SplFileInfo($path);
        $resource = fopen($path, 'r');

        return new static(filename: $file->getFilename(), resource: $resource);
    }
}
