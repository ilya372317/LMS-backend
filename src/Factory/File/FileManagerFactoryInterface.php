<?php

namespace App\Factory\File;

use App\Persist\File\FileToDatabasePersistInterface;
use App\Service\File\FileUploaderInterface;

/**
 * Abstract factory for get a database saver uploader to different type of files.
 *
 * @author Ilya Otinov
 * @email ilya.otinov@gmail.com
 */
interface FileManagerFactoryInterface
{
    /**
     * @return FileToDatabasePersistInterface - concrete implementation of FileToDatabasePersistInterface.
     */
    public function getPersist(): FileToDatabasePersistInterface;

    /**
     * @return FileUploaderInterface - concrete implementation of FileUploaderInterface.
     */
    public function getUploader(): FileUploaderInterface;
}