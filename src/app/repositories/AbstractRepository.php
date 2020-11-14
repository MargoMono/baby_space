<?php

namespace App\Repository;

use App\Components\Db;
use App\Log\Logger;

abstract class AbstractRepository
{
    protected $db;
    protected $logger;

    public function __construct()
    {
        $this->db = Db::getConnection();
        $this->logger = Logger::getLogger(static::class);
    }

    public function getArrayWithIdAsKey($array)
    {
        $newArray = [];
        foreach ($array as $id => $item) {
            $newArray[$item['id']] = $item;
        }

        return $newArray;
    }
}