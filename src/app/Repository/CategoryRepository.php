<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class CategoryRepository extends AbstractRepository implements Entity
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'c.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
            c.*, cd.name AS name, cd.short_description AS short_description,
            cd.description AS description,
            f.alias AS file_alias
        FROM category c
            JOIN category_description cd ON cd.category_id = c.id
            JOIN file f ON c.file_id = f.id
        WHERE cd.language_id = :language_id
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
            c.*, cd.name AS name, cd.short_description AS short_description,
            cd.description AS description,
            f.alias AS file_alias
        FROM category c
            JOIN category_description cd ON cd.category_id = c.id
            JOIN file f ON c.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO category 
    (parent_id, file_id, status, alias) 
VALUES 
    (:parent_id, :file_id, :status, :alias) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':alias', $data['alias']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create category');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE category
    SET
    parent_id = :parent_id,
    file_id = :file_id,
    status = :status,
    alias = :alias
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update category');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM category WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['id' => $id]);
            throw new \RuntimeException('Unable to delete category');
        }
    }

    public function getFileByEntityId($id)
    {
        $sql = '
        SELECT f.*
            FROM category c
            JOIN file f ON c.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getAllAvailable($languageId = null)
    {
        $languageId = $languageId ?? Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
            c.*, cd.name AS name, cd.short_description AS short_description,
            cd.description AS description,
            f.alias AS file_alias
        FROM category c
            JOIN category_description cd ON cd.category_id = c.id
            LEFT JOIN file f ON c.file_id = f.id
        WHERE c.status = 1
            AND cd.language_id = :language_id ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}
