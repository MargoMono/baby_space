<?php

namespace App\Repository;

use PDO;
use PDOException;

class CommentDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($commentId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM comment_description
        WHERE comment_id = :comment_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$commentId, $languageIg]);
            throw new \RuntimeException('Unable to load comment description');
        }
    }

    public function create($commentId, $data)
    {
        $sql = '
INSERT INTO comment_description
    (comment_id, language_id, description) 
VALUES 
    (:comment_id, :language_id, :description) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_id', $commentId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':description', $data['description']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create comment description');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE comment_description
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
            throw new \RuntimeException('Unable to update comment description');
        }
    }
}

