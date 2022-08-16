<?php

namespace App\Image;

use App\Entity\Image;
use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;
use Xaduken\ImageSupport\Factory\ImageManagerFactoryInterface;
use Xaduken\ImageSupport\Factory\SimpleImageManagerFactory;
use Xaduken\ImageSupport\Service\AbstractImageManager;

class LessonImageManager extends AbstractImageManager
{
    private EntityManagerInterface $entityManager;

    private string $targetDirectory;

    public function __construct(EntityManagerInterface $entityManager, string $targetDirectory)
    {
        $this->entityManager = $entityManager;
        $this->targetDirectory = $targetDirectory;
    }

    protected function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    protected function getRelatedClass(): string
    {
        return Lesson::class;
    }

    protected function getImageManagerFactory(): ImageManagerFactoryInterface
    {
        return new SimpleImageManagerFactory($this->getEntityManager());
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getImageEntityClass(): string
    {
        return Image::class;
    }
}