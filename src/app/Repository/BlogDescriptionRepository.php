<?php

namespace App\Repository;

use PDO;
use PDOException;

class BlogDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($blogId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM blog_description
        WHERE blog_id = :blog_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':blog_id', $blogId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $blogId);
            throw new \RuntimeException('Unable to load blog description');
        }
    }

    public function create($blogId, $data)
    {
        $sql = '
INSERT INTO blog_description
    (blog_id, language_id, name, short_description, description, tag, meta_title, meta_description, meta_keyword) 
VALUES 
    (:blog_id, :language_id, :name, :short_description, :description, :tag, :meta_title, :meta_description, :meta_keyword) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':blog_id', $blogId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':short_description', $data['short_description']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':tag', $data['tag']);
        $result->bindParam(':meta_title', $data['meta_title']);
        $result->bindParam(':meta_description', $data['meta_description']);
        $result->bindParam(':meta_keyword', $data['meta_keyword']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create blog description');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE blog_description
    SET
    name = :name,
    short_description = :short_description,
    description = :description,
    tag = :tag,
    meta_title = :meta_title,
    meta_description = :meta_description,
    meta_keyword = :meta_keyword
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':short_description', $data['short_description']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':tag', $data['tag']);
        $result->bindParam(':meta_title', $data['meta_title']);
        $result->bindParam(':meta_description', $data['meta_description']);
        $result->bindParam(':meta_keyword', $data['meta_keyword']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update blog description');
        }
    }
}

