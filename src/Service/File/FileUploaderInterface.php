<?php

namespace App\Service\File;

use App\DTO\FileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface describe methods to upload UploadedFile to filesystem.
 *
 * @author Ilya Otinov
 * @email ilya.otinov@gmail.com
 */
interface FileUploaderInterface
{
    /**
     * Upload file to filesystem and return information about him.
     *
     * @param  UploadedFile  $file
     * @return FileInfo - DTO object with file information.
     */
    public function upload(UploadedFile $file): FileInfo;

    /**
     * @return string - full path to directory
     */
    public function getTargetDirectory(): string;
}
