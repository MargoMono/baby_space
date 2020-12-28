<?php

namespace App\Repository;

interface Entity
{
    public function getAll($sort = null);

    public function getById($id);

    public function create($data);

    public function updateById($id);

    public function deleteById($id);

    public function getFileByEntityId($id);
}
