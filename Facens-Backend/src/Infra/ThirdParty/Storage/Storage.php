<?php

namespace App\Infra\ThirdParty\Storage;

use App\Domain\Entity\File;

interface Storage
{
    public function upload(File $file): File;
    public function generateTemporaryLink(File $file): File;
    public function generateTemporaryLinkFromPath(string $path): string;
    public function getFileInBase64(File $file): File;
    public function createBucket(?string $bucketName = null): void;
    public function delete(File $file): void;
}
