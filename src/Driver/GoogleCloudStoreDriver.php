<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 20.08.18
 * Time: 13:00
 */

namespace Phore\CloudStore\Driver;


use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;

class GoogleCloudStoreDriver implements CloudStoreDriver
{

    /**
     * @var \Google\Cloud\Storage\Bucket
     */
    private $bucket;

    public function __construct(string $keyFilePath, string $bucketName)
    {
        if ( ! class_exists(StorageClient::class))
            throw new \InvalidArgumentException("Package google/cloud-storage is missing. Install it by running 'composer install google/cloud-storage'");
        $store = new StorageClient([
            "keyFilePath" => __DIR__ . "/../etc/talpa-backend-a938dc597171.json"
        ]);

        $this->bucket = $store->bucket("talpa-maschine-data");
    }


    public function has(string $objectId): bool
    {
        return $this->bucket->object($objectId)->exists();
    }

    public function put(string $objectId, $content)
    {
        $this->bucket->upload($content, [
            "name" => $objectId,
            'predefinedAcl' => 'projectprivate'
        ]);
    }

    public function get(string $objectId): string
    {
        return $this->bucket->object($objectId)->downloadAsString();
    }

    public function getBucket() : Bucket
    {
        return $this->bucket;
    }

}
