<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 20.08.18
 * Time: 13:00
 */

namespace Phore\CloudStore\Driver;


use Google\Cloud\Core\Exception\NotFoundException;
use Google\Cloud\Storage\Bucket;
use Google\Cloud\Storage\StorageClient;
use Psr\Http\Message\StreamInterface;

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
            "keyFilePath" => $keyFilePath
        ]);

        $this->bucket = $store->bucket($bucketName);
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

    /**
     * @param string $objectId
     * @return StreamInterface
     * @throws \Phore\CloudStore\NotFoundException
     */
    public function get(string $objectId): string
    {
        try {
            return $this->bucket->object($objectId)->downloadAsString();
        } catch (NotFoundException $e) {
            throw new \Phore\CloudStore\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getBucket() : Bucket
    {
        return $this->bucket;
    }

    public function putStream(string $objectId, $ressource)
    {
        $this->bucket->upload($ressource, [
            "name" => $objectId,
            'predefinedAcl' => 'projectprivate'
        ]);
    }

    /**
     * @param string $objectId
     * @return StreamInterface
     * @throws \Phore\CloudStore\NotFoundException
     */
    public function getStream(string $objectId) : StreamInterface
    {
        try {
            return $this->bucket->object($objectId)->downloadAsStream();
        } catch (NotFoundException $e) {
            throw new \Phore\CloudStore\NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
