<?php

namespace App\Repository;

use App\Repository\AbstractRepository;
use PDO;

class CategoryRepository extends AbstractRepository implements Entity
{
    public function getById($id)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'b.id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $sql = '
        SELECT 
            c.*, cp.name AS parent_name, f.alias AS file_alias
        FROM category c
            LEFT JOIN category cp ON c.parent_id = cp.id
            LEFT JOIN file f ON c.file_id = f.id
        WHERE c.id = :id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];

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
        AND c.status = 1';

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

    public function getMainCategoryList()
    {
        $result = $this->db->query('
        SELECT c.*, f.alias AS file_alias
            FROM category c
            JOIN file f ON c.file_id = f.id 
        WHERE c.status = 1 
            AND c.parent_id = 0
        ORDER BY c.name'
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
            AND cp.status = 1';

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

        return $result->execute();
    }


    public function getFileByEntityId($id)
    {
        $sql = '
        SELECT f.*
            FROM language b
            LEFT JOIN file f ON b.file_id = f.id
        WHERE b.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}
