<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 20.08.18
 * Time: 13:05
 */

namespace Phore\CloudStore;


use GuzzleHttp\Psr7\Stream;
use Phore\CloudStore\Driver\CloudStoreDriver;
use Psr\Http\Message\StreamInterface;

class ObjectStore
{

    /**
     * @var CloudStoreDriver
     */
    private $driver;


    public function __construct(CloudStoreDriver $cloudStoreDriver)
    {
        $this->driver = $cloudStoreDriver;
    }


    public function getDriver()
    {
        return $this->driver;
    }


    public function has(string $objectId) : bool
    {
        return $this->driver->has($objectId);
    }


    /**
     * @param string $objectId
     * @return string
     * @throws NotFoundException
     */
    public function get(string $objectId) : string
    {
        return $this->driver->get($objectId);
    }


    /**
     * @param string $objectId
     * @return array
     * @throws NotFoundException
     */
    public function getJson (string $objectId) : array
    {
        $data = $this->get($objectId);
        $ret = json_decode($data, true);
        if ($ret === null)
            throw new \InvalidArgumentException("Cannot json-decode data from object '$objectId'");
        return $ret;
    }

    public function put (string $objectId, string $data)
    {
        $this->driver->put($objectId, $data);
    }

    public function putStream (string $objectId, $ressource)
    {
        $this->driver->putStream($objectId, $ressource);
    }

    /**
     * @param string $objectId
     * @return StreamInterface
     * @throws NotFoundException
     */
    public function getStream(string $objectId) : StreamInterface
    {
        return $this->driver->getStream($objectId);
    }

    public function putJson (string $objectId, array $data)
    {
        $this->driver->put($objectId, json_encode($data));
    }
}
