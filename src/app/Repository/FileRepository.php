<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class FileRepository extends AbstractRepository
{
    public function createFile($image)
    {
        $sql = "
            INSERT INTO file
                (alias, name, created_at)
            VALUES 
                (:alias, :name, now())";

        $result = $this->db->prepare($sql);
        $result->bindParam(':alias', $image['alias']);
        $result->bindParam(':name', $image['name']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function getFileById($id)
    {
        $sql = '
        SELECT 
            *
        FROM file 
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}