<?php

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function upload(UploadedFile $file): string;

    public function getTargetDirectory(): string;
}