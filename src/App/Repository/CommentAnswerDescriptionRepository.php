<?php

namespace App\Repository;

use PDO;
use PDOException;

class CommentAnswerDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($commentAnswerId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM comment_answer_description
        WHERE comment_answer_id = :comment_answer_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_answer_id', $commentAnswerId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$commentAnswerId, $languageIg]);
            throw new \RuntimeException('Unable to load comment answer description');
        }
    }

    public function create($commentAnsweId, $data)
    {
        $sql = '
        INSERT INTO comment_answer_description
            (comment_answer_id, language_id, description) 
        VALUES 
            (:comment_answer_id, :language_id, :description)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':comment_answer_id', $commentAnsweId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':description', $data['description']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create comment answer description');
        }
    }

    public function updateById($data)
    {
        $sql = '
        UPDATE comment_answer_description
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
            throw new \RuntimeException('Unable to update comment answer description');
        }
    }
}
