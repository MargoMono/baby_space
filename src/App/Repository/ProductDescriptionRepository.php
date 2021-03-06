<?php

namespace App\Repository;

use PDO;
use PDOException;

class ProductDescriptionRepository extends AbstractRepository
{
    public function getByIdAndLanguageId($productId, $languageIg)
    {
        $sql = '
        SELECT 
           *
        FROM product_description 
        WHERE product_id = :product_id
        AND language_id = :language_id
        ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':language_id', $languageIg);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        try {
            $result->execute();
            return $result->fetch();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), $productId);
            throw new \RuntimeException('Unable to load product description');
        }
    }

    public function create($productId, $data)
    {
        $sql = '
INSERT INTO product_description
    (product_id, language_id, name, description, tag, meta_title, meta_description, meta_keyword) 
VALUES 
    (:product_id, :language_id, :name, :description, :tag, :meta_title, :meta_description, :meta_keyword) ';

        $result = $this->db->prepare($sql);
        $result->bindParam(':product_id', $productId);
        $result->bindParam(':language_id', $data['language_id']);
        $result->bindParam(':name', $data['name']);
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
            throw new \RuntimeException('Unable to create product description');
        }
    }

    public function updateById($data)
    {
        $sql = '
UPDATE product_description
    SET
    name = :name,
    description = :description,
    tag = :tag,
    meta_title = :meta_title,
    meta_description = :meta_description,
    meta_keyword = :meta_keyword
WHERE id = :id';

        $result = $this->db->prepare($sql);
        $result->bindParam(':name', $data['name']);
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
            throw new \RuntimeException('Unable to update product');
        }
    }
}

