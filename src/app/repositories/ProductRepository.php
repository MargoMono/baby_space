<?php

namespace App\Repository\Site;

use App\Repository\Repository;
use PDO;

class ProductRepository extends Repository
{
    public function getProductById($id)
    {
        $sql = '
        SELECT 
            c.*, cp.name AS category_name, f.alias AS file_alias
        FROM product c
            JOIN category cp ON c.category_id = cp.id
            JOIN file f ON c.file_id = f.id
        WHERE c.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getEnableProductById($id)
    {
        $sql = '
        SELECT 
            p.*, cp.name AS category_name, f.alias AS file_alias
        FROM product p
            JOIN category cp ON p.category_id = cp.id
            JOIN file f ON p.file_id = f.id
        WHERE p.id = :id
        AND p.enabled = 1';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function getProductList($order = null)
    {
        if (empty($order)) {
            $order = 'id';
        }

        $sql = '
        SELECT 
            p.*, f.alias AS file_alias, c.name AS category_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
        ORDER BY ' . $order;

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllEnabledProductListByCategory($categoryId)
    {
        $sql = '
        SELECT 
            p.*, f.alias as file_alias
        FROM product p 
            JOIN category c ON p.category_id = c.id 
            JOIN file f ON p.file_id = f.id 
        WHERE p.enabled = 1 
            AND p.category_id = :category_id 
        ORDER BY p.position ASC ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getEnabledProductListByCategory($categoryId, $limit)
    {
        $sql = '
        SELECT 
            p.*, f.alias as file_alias
        FROM product p 
            JOIN category c ON p.category_id = c.id 
            JOIN file f ON p.file_id = f.id 
        WHERE p.enabled = 1 
            AND p.category_id = :category_id 
        ORDER BY p.position ASC 
        LIMIT :limit';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getMoreEnabledProductsByCategory($categoryId, $count, $limit)
    {
        $sql = '
        SELECT 
            p.*, f.alias as file_alias
        FROM product p 
            JOIN category c ON p.category_id = c.id 
            JOIN file f ON p.file_id = f.id 
        WHERE p.enabled = 1 
            AND p.category_id = :category_id 
        ORDER BY p.position ASC 
        LIMIT ' . $limit . ' OFFSET ' . $count;

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createProduct($data)
    {
        $sql = '
INSERT INTO product 
    (category_id, name, description, content, file_id, enabled, alias, position, tag_title, tag_description, tag_keywords) 
VALUES 
    (:category_id, :name, :description, :content, :file_id, :enabled, :alias, :position, :tag_title, :tag_description, :tag_keywords) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':content', $data['content']);
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

    public function updateProductById($data)
    {
        $sql = '
UPDATE product
    SET
    category_id = :category_id,
    name = :name,
    description = :description,
    content = :content,
    file_id = :file_id,
    enabled = :enabled,
    alias = :alias,
    position = :position,
    tag_title = :tag_title,
    tag_description = :tag_description,
    tag_keywords = :tag_keywords
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':name', $data['name']);
        $result->bindParam(':description', $data['description']);
        $result->bindParam(':content', $data['content']);
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

    public function deleteProductById($id)
    {
        $sql = 'DELETE FROM product WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function getEnabledProductListByIds($ids, $limit)
    {
        $sql = '
        SELECT 
            p.*, f.alias as file_alias
        FROM product p 
            JOIN file f ON p.file_id = f.id 
        WHERE p.enabled = 1 
            AND p.id IN (' . $ids . ') 
        ORDER BY p.position ASC 
        LIMIT :limit';

        $result = $this->db->prepare($sql);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllEnabledProductListByIds($ids)
    {
        $sql = '
        SELECT 
            p.*, f.alias as file_alias
        FROM product p 
            JOIN file f ON p.file_id = f.id 
        WHERE p.enabled = 1 
             AND p.id IN (' . $ids . ') 
        ORDER BY p.position ASC ';

        $result = $this->db->prepare($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function createFilesProductConnection($productId, $fileId)
    {
        $sql = '
INSERT INTO product_file
    (product_id, file_id) 
VALUES 
    (:product_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':file_id', $fileId);

        if ($result->execute()) {
            return $this->db->lastInsertId();
        }

        return null;
    }

    public function getProductFilesByProductId($id)
    {
        $sql = '
        SELECT 
            p.*, f.alias AS file_alias, f.id AS file_id
        FROM product p
            LEFT JOIN product_file pf ON p.id = pf.product_id
            LEFT JOIN file f ON pf.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function deleteFilesProductConnectionByProductId($id)
    {
        $sql = 'DELETE FROM product_file WHERE product_id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        return $result->execute();
    }

    public function deleteFileProductConnection($productId, $fileId)
    {
        $sql = 'DELETE FROM product_file WHERE product_id = :product_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':file_d', $fileId);

        return $result->execute();
    }
}

