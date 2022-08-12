<?php

namespace App\DTO;

class FileInfo
{
    protected string $filename;

    protected string $targetDirectory;

    protected string $mimeType;

    public function __construct(string $fileName, string $targetDirectory, string $mimeType)
    {
        $this->filename = $fileName;
        $this->targetDirectory = $targetDirectory;
        $this->mimeType = $mimeType;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param  string  $filename
     * @return FileInfo
     */
    public function setFilename(string $filename): FileInfo
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * @param  string  $targetDirectory
     * @return FileInfo
     */
    public function setTargetDirectory(string $targetDirectory): FileInfo
    {
        $this->targetDirectory = $targetDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param  string  $mimeType
     * @return FileInfo
     */
    public function setMimeType(string $mimeType): FileInfo
    {
        $this->mimeType = $mimeType;
        return $this;
    }
}
