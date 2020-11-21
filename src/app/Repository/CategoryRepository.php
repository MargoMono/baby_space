<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class CategoryRepository extends AbstractRepository implements Entity
{
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

    public function getEnabledCategoryById($id)
    {
        $sql = '
        SELECT 
            c.*, cp.name AS parent_name, f.alias AS file_alias
        FROM category c
            LEFT JOIN category cp ON c.parent_id = cp.id
            LEFT JOIN file f ON c.file_id = f.id
        WHERE c.id = :id
        AND c.enabled = 1';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getAll($sort = null)
    {
        if (empty($sort)) {
            $sort = 'id';
        }

        $sql = '
        SELECT 
            c.*, cp.name AS parent_name, cp.id AS parent_id
        FROM category c
            LEFT JOIN category cp ON c.parent_id = cp.id
        ORDER BY ' . $sort;

        $result = $this->db->prepare($sql);
        $result->bindParam(':order', $sort);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getEnabledCategoryList()
    {
        $sql = '
        SELECT c.*, cp.name AS parent_name 
            FROM category c
            LEFT JOIN category cp ON c.parent_id = cp.id
        WHERE c.enabled = 1
        ORDER BY c.position ';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMainCategoryList()
    {
        $result = $this->db->query('
        SELECT c.*, f.alias AS file_alias
            FROM category c
            JOIN file f ON c.file_id = f.id 
        WHERE c.enabled = 1 
            AND c.parent_id = 0
        ORDER BY c.position, c.name'
        );

        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetchAll();
    }

    public function getChildCategoryListById($id)
    {
        $sql = '
        SELECT 
            cp.*, f.alias as file_alias
        FROM category c
            JOIN category cp ON cp.parent_id = c.id
            JOIN file f ON cp.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getEnableChildCategoryListById($id)
    {
        $sql = '
        SELECT 
            cp.*, f.alias as file_alias
        FROM category c
            JOIN category cp ON cp.parent_id = c.id
            JOIN file f ON cp.file_id = f.id
        WHERE c.id = :id
            AND cp.enabled = 1
        ORDER BY position';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getParentCategoryListById($id)
    {
        $sql = '
        SELECT cp.*, f.alias as file_alias
            FROM category c
            JOIN category cp ON c.parent_id = cp.id
            JOIN file f ON cp.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM category WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO category 
    (parent_id, name, description, file_id, enabled, alias, position, tag_title, tag_description, tag_keywords) 
VALUES 
    (:parent_id, :name, :description, :file_id, :enabled, :alias, :position, :tag_title, :tag_description, :tag_keywords) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':enabled', $data['enabled']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':position', $data['position']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
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
    enabled = :enabled,
    alias = :alias,
    position = :position,
    tag_title = :tag_title,
    tag_description = :tag_description,
    tag_keywords = :tag_keywords
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':parent_id', $data['parent']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':enabled', $data['enabled']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':position', $data['position']);
        $result->bindParam(':tag_title', $data['tag_title']);
        $result->bindParam(':tag_description', $data['tag_description']);
        $result->bindParam(':tag_keywords', $data['tag_keywords']);
        $result->bindParam(':id', $data['id']);

        return $result->execute();
    }

    public function getCategoryFilesByCategoryId($id)
    {
        $sql = '
        SELECT 
            c.*, f.alias AS file_alias, f.id AS file_id
        FROM category c
            LEFT JOIN category_file cf ON c.id = cf.category_id
            LEFT JOIN file f ON cf.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createFilesConnection($categoryId, $fileId)
    {
        $sql = '
INSERT INTO category_file
    (category_id, file_id) 
VALUES 
    (:category_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId);
        $result->bindParam(':file_id', $fileId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function deleteFileConnection($categoryId, $fileId)
    {
        $sql = 'DELETE FROM category_file WHERE category_id = :category_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId);
        $result->bindParam(':file_d', $fileId);

        return $result->execute();
    }

    public function getFileByEntityId($id)
    {
        // TODO: Implement getFileByEntityId() method.
    }
}
