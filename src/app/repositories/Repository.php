<?php

namespace App\Repository;

interface Repository
{
    public function getCollection($order = null);

    public function getById($id);

    public function create($data);

    public function update($data);

    public function deleteById($data);

}