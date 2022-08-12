<?php

namespace App\Persist\File;

use App\DTO\FileInfo;

/**
 * Interface for persist file information in database.
 *
 * @author Ilya Otinov
 * @email ilya.otinov@gmail.com
 */
interface FileToDatabasePersistInterface
{
    /**
     * Persist information about file to database.
     *
     * @param  FileInfo  $fileInfo - DTO object which contains information about file.
     * @param  array  $parameters - Costume parameters, which needs to create Entity object.
     * @return bool - If persisting was done, return true, else false.
     */
    public function persist(FileInfo $fileInfo, array $parameters): bool;
}
