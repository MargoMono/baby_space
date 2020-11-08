<?php

namespace App\Repository;

interface Repository
{
    public function getAll($order = null);

    public function getById($id);

    public function create($data);

    public function updateById($id);

    public function deleteById($id);
}
