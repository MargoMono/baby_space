<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class ProductRepository extends AbstractRepository
{
    public function getAll($sort = null)
    {
        if (empty($sort['order'])) {
            $sort['order'] = 'id';
        }

        if (empty($sort['desc'])) {
            $sort['desc'] = 'ASC';
        }

        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
            p.*, 
            f.alias AS file_alias, 
            c.name AS category_name,
            pd.description as description, pd.name as product_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
            JOIN product_description pd ON p.id = pd.product_id
        WHERE language_id = :language_id
        ORDER BY ' . $sort['order'] . ' ' . $sort['desc'];


        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getRecomendations($id)
    {
        $languageId = Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
            p.*, 
            f.alias AS file_alias, 
            c.name AS category_name,
            pd.description as description, pd.name as product_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
            JOIN product_description pd ON p.id = pd.product_id
        WHERE language_id = :language_id
        AND p.id != :id';


        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id)
    {
        $sql = '
        SELECT 
            p.*, cp.id as category_id, cp.name AS category_name, f.alias AS file_alias
        FROM product p
            JOIN category cp ON p.category_id = cp.id
            JOIN file f ON p.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public function create($data)
    {
        $sql = '
INSERT INTO product 
    (category_id, price, sale, file_id, status, alias, sort) 
VALUES 
    (:category_id, :price, :sale, :file_id, :status, :alias, :sort) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':price', $data['price']);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':sort', $data['sort']);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to create product');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE product
    SET
    category_id = :category_id,
    price = :price,
    sale = :sale,
    file_id = :file_id,
    status = :status,
    alias = :alias,
    sort = :sort
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':price', $data['price']);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':alias', $data['alias']);
        $result->bindParam(':sort', $data['sort']);
        $result->bindParam(':id', $data['id']);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $data);
            throw new \RuntimeException('Unable to update product');
        }
    }

    public function deleteById($id)
    {
        $sql = 'DELETE FROM product WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage());
            throw new \RuntimeException('Unable to delete product');
        }
    }

    public function createFilesConnection($productId, $fileId)
    {
        $sql = '
INSERT INTO product_file
    (product_id, file_id) 
VALUES 
    (:product_id, :file_id) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':file_id', $fileId);

        try {
            $result->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$productId, $fileId]);
            throw new \RuntimeException('Unable to create product');
        }
    }

    public function getProductFilesByProductId($id)
    {
        $sql = '
        SELECT 
            p.*, f.alias AS file_alias, f.id AS file_id
        FROM product p
            JOIN product_file pf ON p.id = pf.product_id
            JOIN file f ON pf.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function deleteFileConnection($productId, $fileId)
    {
        $sql = 'DELETE FROM product_file WHERE product_id = :product_id AND file_id =:file_d';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':file_d', $fileId);

        try {
            $result->execute();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$productId, $fileId]);
            throw new \RuntimeException('Unable to delete product-file connection');
        }
    }

    public function getFile($id)
    {
        $sql = '
        SELECT 
            f.*
        FROM product p
            JOIN file f ON p.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getFiles($id)
    {
        $sql = '
        SELECT 
            f.*
        FROM product p
            JOIN product_file pf ON p.id = pf.product_id
            JOIN file f ON pf.file_id = f.id
        WHERE p.id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getFilteredData($data)
    {
        $languageId = Language::DEFAUL_LANGUGE_ID;

        $filter = '';

        if (!empty($data['name'])) {
            $filter .= ' AND pd.name like \'%' . $data['name'] . '%\'';
        }

        if (!empty($data['category'])) {
            $filter .= ' AND c.id = ' . $data['category'];
        }

        if (!empty($data['price'])) {
            $filter .= ' AND p.price = ' . $data['price'];
        }

        if ($data['status'] !== '') {
            $filter .= ' AND p.status = ' . $data['status'];
        }

        $sql = "
        SELECT 
            p.id
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
            JOIN product_description pd ON p.id = pd.product_id
        WHERE language_id = :language_id
        $filter";

        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }
}

