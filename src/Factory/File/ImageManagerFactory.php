<?php

namespace App\Factory\File;

use App\Persist\File\FileToDatabasePersistInterface;
use App\Persist\File\ImageToDatabasePersist;
use App\Service\File\FileUploader;
use App\Service\File\FileUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;

class ImageManagerFactory implements FileManagerFactoryInterface
{
    private EntityManagerInterface $entityManager;

    private string $fileDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $fileDirectory)
    {
        $this->entityManager = $entityManager;
        $this->fileDirectory = $fileDirectory;
    }

    /**
     * @inheritDoc
     */
    public function getPersist(): FileToDatabasePersistInterface
    {
        return new ImageToDatabasePersist($this->entityManager);
    }

    /**
     * @inheritDoc
     */
    public function getUploader(): FileUploaderInterface
    {
        return new FileUploader($this->fileDirectory);
    }
}