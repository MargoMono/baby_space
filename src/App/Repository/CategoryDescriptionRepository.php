<?php

namespace App\Repository;

use PDO;
use PDOException;

class CategoryDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($categoryId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM category_description 
        WHERE category_id = :category_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [
                'category_id' => $categoryId,
                'language_id' => $languageIg
            ]);
            throw new \RuntimeException('Unable to load category description');
        }
    }

    public function create($categoryId, $data)
    {
        $sql = '
INSERT INTO category_description
    (category_id, language_id, name, short_description) 
VALUES 
    (:category_id, :language_id, :name, :short_description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':short_description', $data['short_description']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create category description');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE category_description
            SET
            name = :name,
            short_description = :short_description
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':short_description', $data['short_description']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update category description');
        }
    }
}

