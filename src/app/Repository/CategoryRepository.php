<?php

namespace App\Repository;

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

        $sql = '
        SELECT 
            c.*, cp.name AS parent_name, cp.id AS parent_id, f.alias AS file_alias
        FROM category c
            LEFT JOIN category cp ON c.parent_id = cp.id
            LEFT JOIN file f ON c.file_id = f.id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT 
            c.*, cp.name AS parent_name, f.alias AS file_alias
        FROM category c
            LEFT JOIN category cp ON c.parent_id = cp.id
            LEFT JOIN file f ON c.file_id = f.id
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
    (parent_id, name, description, file_id, status, alias, tag, meta_title, meta_description, meta_keyword) 
VALUES 
    (:parent_id, :name, :description, :file_id, :status, :alias, :tag, :meta_title, :meta_description, :meta_keyword) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag', $data['tag']);
        $result->bindParam(':meta_title', $data['meta_title']);
        $result->bindParam(':meta_description', $data['meta_description']);
        $result->bindParam(':meta_keyword', $data['meta_keyword']);

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
    name = :name,
    description = :description,
    file_id = :file_id,
    status = :status,
    alias = :alias,
    tag = :tag,
    meta_title = :meta_title,
    meta_description = :meta_description,
    meta_keyword = :meta_keyword
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':tag', $data['tag']);
        $result->bindParam(':meta_title', $data['meta_title']);
        $result->bindParam(':meta_description', $data['meta_description']);
        $result->bindParam(':meta_keyword', $data['meta_keyword']);
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
            LEFT JOIN file f ON c.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
