<?php

namespace App\Repository;

use App\Components\Language;
use PDO;
use PDOException;

class ProductRepository extends AbstractRepository implements Entity
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
            cd.name AS category_name,
            pd.description as description, pd.name as product_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN product_description pd ON p.id = pd.product_id
            JOIN category c ON p.category_id = c.id 
            JOIN category_description cd ON cd.category_id = c.id 
        WHERE pd.language_id = :language_id
            AND cd.language_id = :language_id
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
            cd.name AS category_name,
            pd.description as description, pd.name as product_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN product_description pd ON p.id = pd.product_id
            JOIN category c ON p.category_id = c.id 
            JOIN category_description cd ON cd.category_id = c.id 
        WHERE pd.language_id = :language_id
            AND cd.language_id = :language_id
            AND p.id != :id';


        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->bindParam(':id', $id);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getById($id, $params = null)
    {
        $languageId = $params['language_id'] ?? Language::DEFAUL_LANGUGE_ID;

        $sql = '
        SELECT 
            p.*, c.id as category_id, 
            cd.name AS category_name, f.alias AS file_alias, 
            pd.description as description, pd.name as product_name,
            sd.name as size_name, td.name as type_name
        FROM product p
            JOIN category c ON p.category_id = c.id
            JOIN category_description cd ON cd.category_id = c.id
            JOIN file f ON p.file_id = f.id
            JOIN product_description pd ON p.id = pd.product_id
            JOIN size s ON s.id = p.size_id
            JOIN size_description sd ON sd.size_id = p.size_id
            JOIN type t ON t.id = p.type_id
            JOIN type_description td ON td.type_id = p.type_id
        WHERE p.id = :id
        AND pd.language_id = :language_id
        AND cd.language_id = :language_id
        AND td.language_id = :language_id
        AND sd.language_id = :language_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':id', $id);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), [$id, $params]);
            throw new \RuntimeException('Unable to get product');
        }
    }

    public function create($data)
    {
        $sql = '
        INSERT INTO product 
            (category_id, size_id, type_id, price, sale, file_id, status, popular, alias, sort) 
        VALUES 
            (:category_id, :size_id, :type_id, :price, :sale, :file_id, :status, :popular, :alias, :sort)';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':size_id', $data['size_id']);
        $result->bindParam(':type_id', $data['type_id']);
        $result->bindParam(':price', $data['price']);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':popular', $data['popular']);
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
            size_id = :size_id,
            type_id = :type_id,
            price = :price,
            sale = :sale,
            file_id = :file_id,
            status = :status,
            popular = :popular,
            alias = :alias,
            sort = :sort
        WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $data['category_id']);
        $result->bindParam(':size_id', $data['size_id']);
        $result->bindParam(':type_id', $data['type_id']);
        $result->bindParam(':price', $data['price']);
        $result->bindParam(':sale', $data['sale']);
        $result->bindParam(':file_id', $data['file_id']);
        $result->bindParam(':status', $data['status']);
        $result->bindParam(':popular', $data['popular']);
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
            throw new \RuntimeException('Unable to create product-file connection');
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

    public function getAllByCategoryId($categoryId): array
    {

        $sql = '
        SELECT 
            *
        FROM product 
        WHERE category_id = :category_id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':category_id', $categoryId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getAllByParams($params = null, $limit = null, $offset = null)
    {
        $where = '';

        $languageId = $params['language_id'] ?? Language::DEFAUL_LANGUGE_ID;

        if (!empty($params['category_id'])) {
            $where .= " AND c.id = {$params['category_id']}";
        }

        if (!empty($params['size_id'])) {
            $where .= " AND size_id = {$params['size_id']}";
        }

        if (!empty($params['type_id'])) {
            $where .= " AND type_id = {$params['type_id']}";
        }

        if (!empty($params['popular'])) {
            $where .= ' AND popular = 1';
        }

        if (!empty($params['min'])) {
            $where .= " AND p.price BETWEEN {$params['min']} AND {$params['max']}";
        }

        $limitAndOffset = '';

        if (!empty($limit)) {
            $limitAndOffset .= ' LIMIT ' . $limit;

            if (!empty($offset)) {
                $limitAndOffset .= ' OFFSET ' . $offset;
            }
        }

        $sql = '
        SELECT 
            p.*, 
            f.alias AS file_alias, 
            cd.name AS category_name,
            pd.description as description, pd.name as product_name
        FROM product p
            JOIN file f ON p.file_id = f.id 
            JOIN category c ON p.category_id = c.id 
            JOIN category_description cd ON cd.category_id = c.id 
            JOIN product_description pd ON p.id = pd.product_id
        WHERE pd.language_id = :language_id
            AND cd.language_id = :language_id
            ' . $where . '
            AND c.status = 1
            AND p.status = 1
        ORDER BY p.sort' . $limitAndOffset;


        $result = $this->db->prepare($sql);
        $result->bindParam(':language_id', $languageId);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetchAll();
    }

    public function getFileByEntityId($id)
    {
    }
}

