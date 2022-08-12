<?php

namespace App\Persist\File;

use App\DTO\FileInfo;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

class ImageToDatabasePersist extends AbstractFileToDatabasePersist
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * @inheritDoc
     */
    protected function getEntityObject(FileInfo $fileInfo, array $parameters): Image
    {
        $relatedEntity = $parameters['relatedEntity'];
        $image = new Image();
        $image->setRelatedEntity($relatedEntity);
        $image->setPath($fileInfo->getTargetDirectory());
        $image->setFileName($fileInfo->getFilename());
        $image->setMimeType($fileInfo->getMimeType());

        return $image;
    }

    /**
     * @inheritDoc
     */
    protected function givenParameterIsValid(array $parameters): bool
    {
        return isset($parameters['relatedEntity']);
    }
}