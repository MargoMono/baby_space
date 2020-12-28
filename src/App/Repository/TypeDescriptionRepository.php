<?php

namespace App\Repository;

use PDO;
use PDOException;

class TypeDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($typeId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM type_description
        WHERE type_id = :type_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':type_id', $typeId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [
                'type_id' => $typeId,
                'language_id' => $languageIg
            ]);
            throw new \RuntimeException('Unable to load type description');
        }
    }

    public function create($typeId, $data)
    {
        $sql = '
        INSERT INTO type_description
            (type_id, language_id, name) 
        VALUES 
            (:type_id, :language_id, :name) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':type_id', $typeId);
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
        UPDATE type_description
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

