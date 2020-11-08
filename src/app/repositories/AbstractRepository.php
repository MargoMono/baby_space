<?php

namespace App\Repository;

use App\Components\Db;

abstract class AbstractRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
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