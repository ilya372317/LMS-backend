<?php

namespace App\Persist\File;

use App\DTO\FileInfo;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

/**
 * Implementation of FileToDatabasePersistInterface.
 * Define way to persist data to database.
 *
 * @author Ilya Otinov
 * @email ilya.otinov@gmail.com
 */
abstract class AbstractFileToDatabasePersist implements FileToDatabasePersistInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Persist file information to database.
     * Used factory method for have capability storing all types of files.
     *
     * @param  FileInfo  $fileInfo
     * @param  array  $parameters
     * @return bool
     */
    public function persist(FileInfo $fileInfo, array $parameters): bool
    {
        $parametersIsInvalid = !$this->givenParameterIsValid($parameters);

        if ($parametersIsInvalid) {
            throw new RuntimeException('given parameter for file persist is invalid');
        }

        $image = $this->getEntityObject($fileInfo, $parameters);
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Describe how Entity object should be created before they be persisted.
     *
     * @param  FileInfo  $fileInfo - DTO object, with file info.
     * @return object - Return
     */
    abstract protected function getEntityObject(FileInfo $fileInfo, array $parameters): object;

    /**
     * Describe how given parameters should be validated. Particular always it`s be a something like
     * isset(parameters['needleKeyFirst']) && isset(parameters['needleKeySecond']).
     *
     * @param  array  $parameters
     * @return bool
     */
    abstract protected function givenParameterIsValid(array $parameters): bool;
}