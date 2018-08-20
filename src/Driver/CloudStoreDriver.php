<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 20.08.18
 * Time: 13:02
 */

namespace Phore\CloudStore\Driver;


interface CloudStoreDriver
{
    public function has(string $objectId) : bool;
    public function put(string $objectId, $content);
    public function get(string $objectId) : string;
}
