<?php

namespace App\Repository;

use PDO;
use PDOException;

class NewDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($newId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM new_description
        WHERE new_id = :new_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':new_id', $newId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$newId, $languageIg]);
            throw new \RuntimeException('Unable to load new description');
        }
    }

    public function create($newId, $data)
    {
        $sql = '
INSERT INTO new_description
    (new_id, language_id, name, description) 
VALUES 
    (:new_id, :language_id, :name, :description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':new_id', $newId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create new description');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE new_description
    SET
    name = :name,
    description = :description
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update new description');
        }
    }
}

