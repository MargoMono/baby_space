<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class BlogRepository extends AbstractRepository implements Entity
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'b.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.name as name
        FROM blog b
            JOIN file f ON b.file_id = f.id
            JOIN blog_description bd ON b.id = bd.blog_id
        WHERE bd.language_id = :language_id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.description as description, bd.name as name
        FROM blog b
             JOIN file f ON b.file_id = f.id
             JOIN blog_description bd ON b.id = bd.blog_id
        WHERE b.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO blog
    (file_id, alias) 
VALUES 
    (:file_id, :alias) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create blog');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE blog
    SET
    file_id = :file_id,
    alias = :alias
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update blog');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM blog WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete blog');
        }
    }

    public function getFileByEntityId($id)
    {
        $sql = '
        SELECT f.*
            FROM blog b
            LEFT JOIN file f ON b.file_id = f.id
        WHERE b.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getAllByLanguageId($languageId)
    {
        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.name as name
        FROM blog b
            JOIN file f ON b.file_id = f.id
            JOIN blog_description bd ON b.id = bd.blog_id
        WHERE bd.language_id = :language_id
        ORDER BY b.created_at DESC ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getLastByLanguageId($languageId, $limit)
    {
        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.name as name
        FROM blog b
            JOIN file f ON b.file_id = f.id
            JOIN blog_description bd ON b.id = bd.blog_id
        WHERE bd.language_id = :language_id
        ORDER BY b.created_at DESC 
        LIMIT ' . $limit;

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMoreByLanguageId($languageId, $limit, $offset)
    {
        $sql = '
        SELECT 
            b.*, f.alias AS file_alias,
            bd.short_description as short_description, bd.name as name
        FROM blog b
            JOIN file f ON b.file_id = f.id
            JOIN blog_description bd ON b.id = bd.blog_id
        WHERE bd.language_id = :language_id
        ORDER BY b.created_at DESC 
        LIMIT ' . $limit . ' OFFSET ' . $offset;

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getByIdAndLanguageId($id, $languageId)
    {
        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.description as description, bd.name as name
        FROM blog b
             JOIN file f ON b.file_id = f.id
             JOIN blog_description bd ON b.id = bd.blog_id
        WHERE b.id = :id
        AND bd.language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getPrevious($createdAt, $languageId)
    {
        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.description as description, bd.name as name
        FROM blog b
             JOIN file f ON b.file_id = f.id
             JOIN blog_description bd ON b.id = bd.blog_id
        WHERE bd.language_id = :language_id
        AND b.created_at < :created_at
        LIMIT 1';

        $result = $this->db->prepare($sql);
        $result->bindParam(':created_at', $createdAt);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getNext($createdAt, $languageId)
    {
        $sql = '
        SELECT 
               b.*, f.alias AS file_alias,
               bd.short_description as short_description, bd.description as description, bd.name as name
        FROM blog b
             JOIN file f ON b.file_id = f.id
             JOIN blog_description bd ON b.id = bd.blog_id
        WHERE bd.language_id = :language_id
        AND b.created_at > :created_at
        LIMIT 1';

        $result = $this->db->prepare($sql);
        $result->bindParam(':created_at', $createdAt);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
