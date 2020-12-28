<?php

namespace App\Repository;

use PDO;
use PDOException;

class SizeDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($sizeId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM size_description
        WHERE size_id = :size_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':size_id', $sizeId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [
                'size_id' => $sizeId,
                'language_id' => $languageIg
            ]);
            throw new \RuntimeException('Unable to load type description');
        }
    }

    public function create($sizeId, $data)
    {
        $sql = '
        INSERT INTO size_description
            (size_id, language_id, name) 
        VALUES 
            (:size_id, :language_id, :name) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':size_id', $sizeId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create type description');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE size_description
            SET
            name = :name
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update type description');
        }
    }
}

