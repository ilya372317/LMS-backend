<?php

namespace App\Service\File;

use App\DTO\FileInfo;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploader implements FileUploaderInterface
{
    private string $targetDirectory;

    private SluggerInterface $slugger;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = new AsciiSlugger();
    }

    /**
     * @inheritDoc
     */
    public function upload(UploadedFile $file): FileInfo
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file = $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $exception) {
            $logger = new Logger();
            $logger->warning($exception->getMessage());
        }

        $targetDirectory = $this->targetDirectory;
        $mimeType = $file->getMimeType();

        return new FileInfo($fileName, $targetDirectory, $mimeType);
    }

    /**
     * @inheritDoc
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}