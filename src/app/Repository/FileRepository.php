<?php

namespace App\Repository;

use PDO;
use PDOException;

class FileRepository extends AbstractRepository
{
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

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e){
            $this->logger->error($e->getMessage(), $image);
            throw new \RuntimeException('Unable to create product');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM file WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }
}