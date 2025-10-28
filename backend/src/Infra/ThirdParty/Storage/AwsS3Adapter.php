<?php

namespace App\Infra\ThirdParty\Storage;

use App\Domain\Entity\File;

class AwsS3Adapter implements Storage
{
    private string $bucketName;

    public function __construct(private $s3client)
    {
        $this->bucketName = $_ENV['STORAGE_BUCKET'];
    }

    public function upload(File $file): File
    {

        $params = [
            'Bucket' => $_ENV['STORAGE_BUCKET'],
            'Key' => str_replace(DIRECTORY_SEPARATOR, '/', $file->getFullPath()),
        ];

        if ($file->getTempContent()) {
            $params['Body'] = $file->getTempContent();
        } else {
            $params['SourceFile'] = str_replace(DIRECTORY_SEPARATOR, '/', $file->getTempPath());
        }

        $this->s3client->putObject($params);

        return $file;
    }

    public function delete(File $file): void
    {
        $this->s3client->deleteObject([
            'Bucket' => $this->bucketName,
            'Key' => str_replace(DIRECTORY_SEPARATOR, '/', $file->getFullPath()),
        ]);
    }

    public function generateTemporaryLink(File $file): File
    {
        $cmd = $this->s3client->getCommand('GetObject', [
            'Bucket' => $_ENV['STORAGE_BUCKET'],
            'Key' => str_replace(DIRECTORY_SEPARATOR, '/', $file->getFullPath()),
        ]);
        $expirationIn = '+'.$_ENV['DEFAULT_EXPIRATION_TEMPORARY_IMAGE_LINK'].' minutes';
        $tempLink = $this->s3client->createPresignedRequest($cmd, $expirationIn);
        $uri = $tempLink->getUri();

        if (in_array($_ENV['ENV'], ['local'])) {
            $uri = str_replace('localstack_brandscard', 'localhost', $uri);
        }

        $file->setTemporaryLink($uri);

        return $file;
    }

    public function getFileInBase64(File $file): File
    {
        $result = $this->s3client->getObject([
            'Bucket' => $_ENV['STORAGE_BUCKET'],
            'Key' => str_replace(DIRECTORY_SEPARATOR, '/', $file->getFullPath()),
        ]);
        $file->setBase64(base64_encode($result['Body']));

        return $file;
    }

    public function createBucket(?string $bucketName = null): void
    {
        $this->s3client->createBucket(['Bucket' => $bucketName ?? $this->bucketName]);
    }

    public function generateTemporaryLinkFromPath(string $path): string
    {
        $cmd = $this->s3client->getCommand('GetObject', [
            'Bucket' => $_ENV['STORAGE_BUCKET'],
            'Key' => str_replace(DIRECTORY_SEPARATOR, '/', $path),
        ]);
        $expirationIn = '+'.$_ENV['DEFAULT_EXPIRATION_TEMPORARY_IMAGE_LINK'].' minutes';
        $tempLink = $this->s3client->createPresignedRequest($cmd, $expirationIn);
        $uri = $tempLink->getUri();

        if (in_array($_ENV['ENV'], ['local'])) {
            $uri = str_replace('localstack_brandscard', 'localhost', $uri);
        }

        return $uri;
    }
}
