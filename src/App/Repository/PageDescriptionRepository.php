<?php

namespace App\Repository;

use PDO;
use PDOException;

class PageDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($pageId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM page_description 
        WHERE page_id = :page_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':page_id', $pageId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [
                'page_id' => $pageId,
                'language_id' => $languageIg
            ]);
            throw new \RuntimeException('Unable to load page description');
        }
    }

    public function create($pageId, $data)
    {
        $sql = '
INSERT INTO page_description
    (page_id, language_id, description) 
VALUES 
    (:page_id, :language_id, :description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':page_id', $pageId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':description', $data['description']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create page description');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE page_description
            SET
            description = :description
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update page description');
        }
    }
}

