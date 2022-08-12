<?php

namespace App\Service\File;

use App\DTO\FileInfo;
use App\Factory\File\FileManagerFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Simple service class for managing saving all type of files.
 *
 * @author Ilya Otinov
 * @email ilya.otinov@gmail.com
 */
class FileManager
{
    private FileManagerFactoryInterface $fileManagerFactory;

    private UploadedFile $uploadedFile;


    public function __construct(FileManagerFactoryInterface $fileManagerFactory, UploadedFile $uploadedFile)
    {
        $this->fileManagerFactory = $fileManagerFactory;
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * Save file in filesystem and make database post.
     *
     * @param  array  $parameters - expect map, where key is an entity property name and value is they value.
     * @return string - filename which was generated.
     */
    public function save(array $parameters): string
    {
        $fileInfo = $this->uploadToFilesystem();
        $this->storeToDatabase($fileInfo, $parameters);

        return $fileInfo->getFilename();
    }

    private function storeToDatabase(FileInfo $fileInfo, array $parameters): void
    {
        $databaseHelper = $this->fileManagerFactory->getPersist();
        $databaseHelper->persist($fileInfo, $parameters);
    }

    private function uploadToFilesystem(): FileInfo
    {
        $uploader = $this->fileManagerFactory->getUploader();
        return $uploader->upload($this->uploadedFile);
    }
}