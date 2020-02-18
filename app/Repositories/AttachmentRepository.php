<?php

namespace App\Repositories;

use Illuminate\Filesystem\FilesystemManager as Storage;

class AttachmentRepository
{
    public function __construct(Storage $storage)
    {
        $this->disk = $storage->disk('s3');
    }

    /**
     * Upload base64 to S3 and return path.
     *
     * @param string $base64 Base64 string to upload to S3
     *
     * @return string path
     */
    public function uploadBase64($base64)
    {
        list($baseType, $image) = explode(';', $base64);
        list(, $image) = explode(',', $image);

        $path = 'poems/'.rand(111111111, 999999999).'.png';

        $this->disk->put($path, base64_decode($image), 'public');

        return $this->disk->url($path);
    }
}
