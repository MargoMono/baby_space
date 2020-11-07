<?php

namespace App\Components;

abstract class Repository
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