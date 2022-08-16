<?php

namespace App\Image;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Xaduken\ImageSupport\Factory\ImageManagerFactoryInterface;
use Xaduken\ImageSupport\Factory\SimpleImageManagerFactory;
use Xaduken\ImageSupport\Service\AbstractImageManager;

class UserImageManager extends AbstractImageManager
{
    private EntityManagerInterface $entityManager;

    private string $targetDir;

    public function __construct(EntityManagerInterface $entityManager, string $targetDir)
    {
        $this->entityManager = $entityManager;
        $this->targetDir = $targetDir;
    }

    protected function getTargetDirectory(): string
    {
        return $this->targetDir;
    }

    protected function getRelatedClass(): string
    {
        return User::class;
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